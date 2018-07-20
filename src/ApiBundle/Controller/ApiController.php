<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Plik;
use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Exception\BladOdczytuPlikuZDyskuException;
use ApiBundle\Exception\BladZapisuPlikuNaDyskuException;
use ApiBundle\Exception\BrakLacznosciZBazaException;
use ApiBundle\Exception\NiepelneDaneException;
use ApiBundle\Exception\PustaKolekcjaException;
use ApiBundle\Exception\RozmiarKolekcjiPlikowJestZbytDuzyException;
use ApiBundle\Exception\RozmiarPlikuJestZbytDuzyException;
use ApiBundle\Exception\UzytkownikNieIstniejeException;
use ApiBundle\Exception\UzytkownikNiePosiadaUprawnienException;
use ApiBundle\Exception\WarunkiBrzegoweNieZostalySpelnioneException;
use ApiBundle\Exception\ZasobNieIstniejeException;
use ApiBundle\Library\WarunkiBrzegowe\Uprawnienia;
use ApiBundle\Model\DaneWejsciowe\Metody\Upload;
use ApiBundle\Model\FizycznyPlik;
use ApiBundle\Model\ProcesujDaneWejsciowe;
use ApiBundle\RabbitMQ\Producer\EmailProducer;
use ApiBundle\RabbitMQ\Producer\Helper\WysylkaEmailowHelper;
use ApiBundle\Repository\PlikRepository;
use ApiBundle\Repository\UzytkownikRepository;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends FOSRestController
{
    private $plikRepository;
    private $uzytkownik;
    private $plik;
    private $procesorDanychWejsciowych;

    /**
     * @Route("/zasob", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws UzytkownikNieIstniejeException
     */
    public function postZasobAction(Request $request)
    {
        $statusCode = Response::HTTP_CREATED;
        $msg = ['status' => 0];

        $this->plikRepository = $this->getDoctrine()->getRepository(Plik::class);
        $this->uzytkownik = $this->getDoctrine()->getRepository(Uzytkownik::class);

        $this->plik = new FizycznyPlik(0, $this->get('api.kolejki'));
        $this->procesorDanychWejsciowych = new ProcesujDaneWejsciowe();

        try {
            /**
             * @note: Obiekt reprezentujący podstawowe dane wejściowe metody Upload
             */
            $metoda = new Upload($request, $this->get('doctrine'));

            $uprawnienia = new Uprawnienia();
            $uzytkownikMozeKontynuowac = $uprawnienia->sprawdzUprawnieniaDoMetody($metoda);

            if (!$uzytkownikMozeKontynuowac) {
                throw new UzytkownikNiePosiadaUprawnienException();
            }

            $dane = $this->procesorDanychWejsciowych->przygotujDane(
                $metoda, $this->get('api.kontener.parametrow')
            );

            $this->plik->przeniesDoWlasciwegoKatalogu($dane);
            $this->plikRepository->zapiszInformacjeOPlikuWBazie($dane);

            $zasoby = $dane->pobierzDaneWszystkichZapisanychZasobow($dane);

            $dane->wyslijEmailaPoZakonczeniuUploadu($this->get('api.kolejki'), $this->renderView("@Api/Email/upload.html.twig", [
                'uzytkownik' => $dane->pobierzDaneUzytkownika()->getLogin(),
                'lista_plikow' => $zasoby
            ]));

            $msg = ['status' => 1, 'zasoby' => $zasoby];

        } catch (BladZapisuPlikuNaDyskuException $bladZapisuPlikuNaDysku) {
            $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        } catch (RozmiarPlikuJestZbytDuzyException $exception) {
            $statusCode = Response::HTTP_REQUEST_ENTITY_TOO_LARGE;
        } catch (RozmiarKolekcjiPlikowJestZbytDuzyException $exception) {
            $statusCode = Response::HTTP_REQUEST_ENTITY_TOO_LARGE;
        } catch (NiepelneDaneException $exception) {
            $statusCode = Response::HTTP_BAD_REQUEST;
        } catch (BrakLacznosciZBazaException $exception) {
            $statusCode = Response::HTTP_GATEWAY_TIMEOUT;
        } catch (UzytkownikNiePosiadaUprawnienException $exception) {
            $statusCode = Response::HTTP_FORBIDDEN;
        } catch (UzytkownikNieIstniejeException $exception) {
            $statusCode = Response::HTTP_FORBIDDEN;
        } catch (PustaKolekcjaException $exception) {
            $statusCode = Response::HTTP_NOT_ACCEPTABLE;
        } catch (WarunkiBrzegoweNieZostalySpelnioneException $exception) {
            $statusCode = Response::HTTP_PRECONDITION_FAILED;
        } catch (\Exception $exception) {
            $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        }

        return $this->handleView($this->view($msg, $statusCode));
    }

    /**
     * @Route("/zasob", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function getZasobAction(Request $request)
    {
        $statusCode = Response::HTTP_CREATED;
        $msg = ['status' => 0];
        $sciezka = null;

        try {
            $przetworzDane = new ProcesujDaneWejsciowe($this->kontenerParametrow);
            /**
             * @var $uzytkownik UzytkownikRepository
             */
            $uzytkownik = $this->getDoctrine()->getRepository(Uzytkownik::class);
            /**
             * @var $plikRepository PlikRepository
             */
            $plikRepository = $this->getDoctrine()->getRepository(Plik::class);
            $daneWejsciowe = $przetworzDane->przygotujDaneWejscioweDownload($request);

            if (!$uzytkownik->czyIstniejeTakiUzytkownik($daneWejsciowe['uzytkownik']['login'])) {
                throw new UzytkownikNiePosiadaUprawnienException();
            }

            $noweDane['uzytkownik']['id'] = $uzytkownik->pobierzIdUzytkownikaPoLoginie(
                $daneWejsciowe['uzytkownik']['login'], $daneWejsciowe['uzytkownik']['haslo']
            );

            if (!$uzytkownik->czyUzytkownikMozePobracZasob($noweDane['uzytkownik']['id'], $daneWejsciowe['id_zasobu'])) {
                throw new UzytkownikNiePosiadaUprawnienException();
            }

            $sciezkaDoZasobu = $plikRepository->pobierzSciezkeDoZasobu($daneWejsciowe['id_zasobu']);

            $plikFizyczny = new FizycznyPlik;

            if (!$plikFizyczny->czyPlikIstniejeNaDysku($sciezkaDoZasobu)) {
                throw new BladOdczytuPlikuZDyskuException("Plik nie istnieje");
            }

            $sciezka = $sciezkaDoZasobu;

        } catch (BladOdczytuPlikuZDyskuException $bladZapisuPlikuNaDysku) {
            $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        } catch (ZasobNieIstniejeException $bladZapisuPlikuNaDysku) {
            $statusCode = Response::HTTP_NOT_FOUND;
        } catch (NiepelneDaneException $exception) {
            $statusCode = Response::HTTP_BAD_REQUEST;
        } catch (BrakLacznosciZBazaException $exception) {
            $statusCode = Response::HTTP_GATEWAY_TIMEOUT;
        } catch (UzytkownikNiePosiadaUprawnienException $exception) {
            $statusCode = Response::HTTP_FORBIDDEN;
        } catch (UzytkownikNieIstniejeException $exception) {
            $statusCode = Response::HTTP_FORBIDDEN;
        }

        if (!is_null($sciezka)) {
            return new BinaryFileResponse($sciezka);
        }

        return $this->handleView($this->view($msg, $statusCode));
    }

    /**
     * @Route("/zasob", methods={"DELETE"})
     * @param Request $request
     * @return Response
     */
    public function deleteZasobAction(Request $request)
    {
        $statusCode = Response::HTTP_ACCEPTED;
        $msg = ['status' => 0];

        try {
            $przetworzDane = new ProcesujDaneWejsciowe($this->container);
            /**
             * @var $uzytkownik UzytkownikRepository
             */
            $uzytkownik = $this->getDoctrine()->getRepository(Uzytkownik::class);
            /**
             * @var $plikRepository PlikRepository
             */
            $plikRepository = $this->getDoctrine()->getRepository(Plik::class);
            $daneWejsciowe = $przetworzDane->przygotujDaneWejscioweDelete($request);

            if (!$uzytkownik->czyIstniejeTakiUzytkownik($daneWejsciowe['uzytkownik']['login'])) {
                throw new UzytkownikNiePosiadaUprawnienException();
            }

            $noweDane['uzytkownik']['id'] = $uzytkownik->pobierzIdUzytkownikaPoLoginie(
                $daneWejsciowe['uzytkownik']['login'], $daneWejsciowe['uzytkownik']['haslo']
            );

            if (!$uzytkownik->czyUzytkownikMozeUsunacZasob($noweDane['uzytkownik']['id'], $daneWejsciowe['id_zasobu'])) {
                throw new UzytkownikNiePosiadaUprawnienException();
            }

            $plikRepository->usunMiekkoPlik($daneWejsciowe['id_zasobu']);

            $msg = ['status' => 1];
        } catch (BladOdczytuPlikuZDyskuException $bladZapisuPlikuNaDysku) {
            $msg = Response::HTTP_SERVICE_UNAVAILABLE;
        } catch (ZasobNieIstniejeException $bladZapisuPlikuNaDysku) {
            $msg = Response::HTTP_NOT_FOUND;
        } catch (NiepelneDaneException $exception) {
            $msg = Response::HTTP_BAD_REQUEST;
        } catch (BrakLacznosciZBazaException $exception) {
            $msg = Response::HTTP_GATEWAY_TIMEOUT;
        } catch (UzytkownikNiePosiadaUprawnienException $exception) {
            $msg = Response::HTTP_FORBIDDEN;
        } catch (UzytkownikNieIstniejeException $exception) {
            $msg = Response::HTTP_FORBIDDEN;
        }

        return $this->handleView($this->view($msg, $statusCode));
    }

    /**
     * @Route("/zasob", methods={"PUT"})
     * @param Request $request
     * @return Response
     */
    public function putZasobAction(Request $request)
    {
        $statusCode = Response::HTTP_ACCEPTED;
        $msg = ['status' => 0];

        try {
            $przetworzDane = new ProcesujDaneWejsciowe($this->container);
            /**
             * @var $uzytkownik UzytkownikRepository
             */
            $uzytkownik = $this->getDoctrine()->getRepository(Uzytkownik::class);
            /**
             * @var $plikRepository PlikRepository
             */
            $plikRepository = $this->getDoctrine()->getRepository(Plik::class);
            $daneWejsciowe = $przetworzDane->przygotujDaneWejsciowePut($request);

            if (!$uzytkownik->czyIstniejeTakiUzytkownik($daneWejsciowe['uzytkownik']['login'])) {
                throw new UzytkownikNiePosiadaUprawnienException();
            }

            $noweDane['uzytkownik']['id'] = $uzytkownik->pobierzIdUzytkownikaPoLoginie(
                $daneWejsciowe['uzytkownik']['login'], $daneWejsciowe['uzytkownik']['haslo']
            );

            if (!$uzytkownik->czyUzytkownikMozeEdytowacZasob($noweDane['uzytkownik']['id'], $daneWejsciowe['id_zasobu'])) {
                throw new UzytkownikNiePosiadaUprawnienException();
            }

            $plikRepository->zmodyfikujZasob($daneWejsciowe['id_zasobu'], $daneWejsciowe['elementy_do_zmiany']);

            $msg = ['status' => 1, 'id_zasobu' => $daneWejsciowe['id_zasobu']];
        } catch (BladOdczytuPlikuZDyskuException $bladZapisuPlikuNaDysku) {
            $msg = Response::HTTP_SERVICE_UNAVAILABLE;
        } catch (ZasobNieIstniejeException $bladZapisuPlikuNaDysku) {
            $msg = Response::HTTP_NOT_FOUND;
        } catch (NiepelneDaneException $exception) {
            $msg = Response::HTTP_BAD_REQUEST;
        } catch (BrakLacznosciZBazaException $exception) {
            $msg = Response::HTTP_GATEWAY_TIMEOUT;
        } catch (UzytkownikNiePosiadaUprawnienException $exception) {
            $msg = Response::HTTP_FORBIDDEN;
        } catch (UzytkownikNieIstniejeException $exception) {
            $msg = Response::HTTP_FORBIDDEN;
        }
        return $this->handleView($this->view($msg, $statusCode));
    }


    /**
     * @Route("/zasob/strumien", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws UzytkownikNieIstniejeException
     */
    public function postZasobStrumienAction(Request $request)
    {
        $statusCode = Response::HTTP_CREATED;
        $msg = ['status' => 0];

        try {
            $przetworzDane = new ProcesujDaneWejsciowe($this->kontenerParametrow);
            $fizycznyPlik = new FizycznyPlik($this->kontenerParametrow->pobierzParametrZConfigu('maksymalny_rozmiar_pliku_w_megabajtach'));
            $plikRepository = $this->getDoctrine()->getRepository(Plik::class);

            /**
             * @var $uzytkownik UzytkownikRepository
             */
            $uzytkownik = $this->getDoctrine()->getRepository(Uzytkownik::class);

            $daneWejsciowe = $przetworzDane->przygotujDaneWejsciowePatch($request);

            if (!$uzytkownik->czyIstniejeTakiUzytkownik($daneWejsciowe->getDaneUzytkownika()->getLogin())) {
                throw new UzytkownikNiePosiadaUprawnienException();
            }

            $daneWejsciowe->getDaneUzytkownika()->setId($uzytkownik->pobierzIdUzytkownikaPoLoginie(
                $daneWejsciowe->getDaneUzytkownika()->getLogin(), $daneWejsciowe->getDaneUzytkownika()->getHaslo()
            ));
//
//            $fizycznyPlik->zapiszPlikiDoceloweNaDysku($daneWejsciowe);
//            $plikRepository->zapiszInformacjeOPlikuWBazie($daneWejsciowe);
//
//            $zasoby = $przetworzDane->pobierzIdWszystkichZasobowDlaTegoZadania($daneWejsciowe);
//
//            $this->wyslijEmailaZInformacjaOUploadzie($daneWejsciowe, $zasoby);

//            $msg = ['status' => 1, 'zasoby' => $zasoby];
        } catch (BladZapisuPlikuNaDyskuException $bladZapisuPlikuNaDysku) {
            $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        } catch (RozmiarPlikuJestZbytDuzyException $exception) {
            $statusCode = Response::HTTP_REQUEST_ENTITY_TOO_LARGE;
        } catch (NiepelneDaneException $exception) {
            $statusCode = Response::HTTP_BAD_REQUEST;
        } catch (BrakLacznosciZBazaException $exception) {
            $statusCode = Response::HTTP_GATEWAY_TIMEOUT;
        } catch (UzytkownikNiePosiadaUprawnienException $exception) {
            $statusCode = Response::HTTP_FORBIDDEN;
        } catch (UzytkownikNieIstniejeException $exception) {
            $statusCode = Response::HTTP_FORBIDDEN;
        } catch (PustaKolekcjaException $exception) {
            $statusCode = Response::HTTP_NOT_ACCEPTABLE;
        } catch (WarunkiBrzegoweNieZostalySpelnioneException $exception) {
            $statusCode = Response::HTTP_PRECONDITION_FAILED;
        } catch (\Exception $exception) {
            $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        }

        return $this->handleView($this->view($msg, $statusCode));
    }

    /**
     * @Route("/zasob/strumien", methods={"PATCH"})
     * @param Request $request
     * @return Response
     * @throws UzytkownikNieIstniejeException
     */
    public function patchZasobStrumienAction(Request $request)
    {
        $statusCode = Response::HTTP_CREATED;
        $msg = ['status' => 0];

        try {
            $przetworzDane = new ProcesujDaneWejsciowe($this->kontenerParametrow);
            $fizycznyPlik = new FizycznyPlik($this->kontenerParametrow->pobierzParametrZConfigu('maksymalny_rozmiar_pliku_w_megabajtach'));
            $plikRepository = $this->getDoctrine()->getRepository(Plik::class);

            /**
             * @var $uzytkownik UzytkownikRepository
             */
            $uzytkownik = $this->getDoctrine()->getRepository(Uzytkownik::class);

            $daneWejsciowe = $przetworzDane->przygotujDaneWejsciowePatch($request);

            if (!$uzytkownik->czyIstniejeTakiUzytkownik($daneWejsciowe->getDaneUzytkownika()->getLogin())) {
                throw new UzytkownikNiePosiadaUprawnienException();
            }

            $daneWejsciowe->getDaneUzytkownika()->setId($uzytkownik->pobierzIdUzytkownikaPoLoginie(
                $daneWejsciowe->getDaneUzytkownika()->getLogin(), $daneWejsciowe->getDaneUzytkownika()->getHaslo()
            ));
//
//            $fizycznyPlik->zapiszPlikiDoceloweNaDysku($daneWejsciowe);
//            $plikRepository->zapiszInformacjeOPlikuWBazie($daneWejsciowe);
//
//            $zasoby = $przetworzDane->pobierzIdWszystkichZasobowDlaTegoZadania($daneWejsciowe);
//
//            $this->wyslijEmailaZInformacjaOUploadzie($daneWejsciowe, $zasoby);

            $msg = ['status' => 1, 'zasoby' => $zasoby];
        } catch (BladZapisuPlikuNaDyskuException $bladZapisuPlikuNaDysku) {
            $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        } catch (RozmiarPlikuJestZbytDuzyException $exception) {
            $statusCode = Response::HTTP_REQUEST_ENTITY_TOO_LARGE;
        } catch (NiepelneDaneException $exception) {
            $statusCode = Response::HTTP_BAD_REQUEST;
        } catch (BrakLacznosciZBazaException $exception) {
            $statusCode = Response::HTTP_GATEWAY_TIMEOUT;
        } catch (UzytkownikNiePosiadaUprawnienException $exception) {
            $statusCode = Response::HTTP_FORBIDDEN;
        } catch (UzytkownikNieIstniejeException $exception) {
            $statusCode = Response::HTTP_FORBIDDEN;
        } catch (PustaKolekcjaException $exception) {
            $statusCode = Response::HTTP_NOT_ACCEPTABLE;
        } catch (WarunkiBrzegoweNieZostalySpelnioneException $exception) {
            $statusCode = Response::HTTP_PRECONDITION_FAILED;
        } catch (\Exception $exception) {
            $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        }

        return $this->handleView($this->view($msg, $statusCode));
    }
}

/**
 * todo: dodać klucze obce do tabel
 * todo: pierwotna nazwa zapisuje się bez rozszerzenia w bazie i encji
 * todo: supervisor
 */
