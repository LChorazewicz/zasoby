<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Plik;
use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Exception\BladOdczytuPlikuZDyskuException;
use ApiBundle\Exception\BladZapisuPlikuNaDyskuException;
use ApiBundle\Exception\BrakLacznosciZBazaException;
use ApiBundle\Exception\NiepelneDaneException;
use ApiBundle\Exception\RozmiarPlikuJestZbytDuzyException;
use ApiBundle\Exception\UzytkownikNieIstniejeException;
use ApiBundle\Exception\UzytkownikNiePosiadaUprawnienException;
use ApiBundle\Exception\ZasobNieIstniejeException;
use ApiBundle\Model\FizycznyPlik;
use ApiBundle\Model\PrzetworzDane;
use ApiBundle\Repository\PlikRepository;
use ApiBundle\Repository\UzytkownikRepository;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends FOSRestController
{
    /**
     * @Route("/Upload")
     * @param Request $request
     * @return Response
     * @throws UzytkownikNieIstniejeException
     */
    public function getUploadAction(Request $request)
    {
        try {

            $przetworzDane = new PrzetworzDane($this->container);
            $fizycznyPlik = new FizycznyPlik($this->container->getParameter('maksymalny_rozmiar_pliku_w_megabajtach'));
            $plikRepository = $this->getDoctrine()->getRepository(Plik::class);

            /**
             * @var $uzytkownik UzytkownikRepository
             */
            $uzytkownik = $this->getDoctrine()->getRepository(Uzytkownik::class);

            $daneWejsciowe = $przetworzDane->przygotujDaneWejscioweUpload($request);

            $noweDane = $przetworzDane->przetworzDaneWejsciowe($daneWejsciowe);

            if (!$uzytkownik->czyIstniejeTakiUzytkownik($daneWejsciowe['uzytkownik']['login'])) {
                throw new UzytkownikNiePosiadaUprawnienException();
            }

            $noweDane['uzytkownik']['id'] = $uzytkownik->pobierzIdUzytkownikaPoLoginie(
                $daneWejsciowe['uzytkownik']['login'], $daneWejsciowe['uzytkownik']['haslo']
            );

            $fizycznyPlik->zapiszPlikNaDysku($daneWejsciowe, $noweDane);
            $plikRepository->zapiszPlikiNaDyskuIUzupelnijDaneWBazie($noweDane, $przetworzDane);

            $zasoby = [];

            foreach ($noweDane['pliki'] as $plik){
                $zasoby[] = [
                    'id_zasobu' => $plik['id_zasobu'],
                    'pierwotna_nazwa' => $plik['nazwa']['pierwotna_z_rozszerzeniem']
                ];
            }

        } catch (BladZapisuPlikuNaDyskuException $bladZapisuPlikuNaDysku) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_SERVICE_UNAVAILABLE));
        } catch (RozmiarPlikuJestZbytDuzyException $exception) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_REQUEST_ENTITY_TOO_LARGE));
        } catch (NiepelneDaneException $exception) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_BAD_REQUEST));
        } catch (BrakLacznosciZBazaException $exception) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_GATEWAY_TIMEOUT));
        } catch (UzytkownikNiePosiadaUprawnienException $exception) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_FORBIDDEN));
        } catch (UzytkownikNieIstniejeException $exception) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_FORBIDDEN));
        }

        return $this->handleView($this->view([
            'status' => 1,
            'zasoby' => $zasoby
        ], Response::HTTP_CREATED));
    }

    /**
     * @Route("/Download")
     * @param Request $request
     * @return Response
     */
    public function getDownloadAction(Request $request)
    {
        try{
            $przetworzDane = new PrzetworzDane($this->container);
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

            $sciezkaDoZasobu = $plikRepository->pobierzSciezkeDoZasobu($daneWejsciowe['id_zasobu']);

            $plikFizyczny = new FizycznyPlik("");

            if(!$plikFizyczny->czyPlikIstniejeNaDysku($sciezkaDoZasobu)){
                throw new BladOdczytuPlikuZDyskuException("Plik nie istnieje");
            }

        } catch (BladOdczytuPlikuZDyskuException $bladZapisuPlikuNaDysku) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_SERVICE_UNAVAILABLE));
        } catch (ZasobNieIstniejeException $bladZapisuPlikuNaDysku) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_NOT_FOUND));
        } catch (NiepelneDaneException $exception) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_BAD_REQUEST));
        } catch (BrakLacznosciZBazaException $exception) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_GATEWAY_TIMEOUT));
        } catch (UzytkownikNiePosiadaUprawnienException $exception) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_FORBIDDEN));
        } catch (UzytkownikNieIstniejeException $exception) {
            return $this->handleView($this->view(['status' => 0], Response::HTTP_FORBIDDEN));
        }
        return new BinaryFileResponse($sciezkaDoZasobu);
    }
}
