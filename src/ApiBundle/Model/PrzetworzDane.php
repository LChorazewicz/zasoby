<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.06.18
 * Time: 16:58
 */

namespace ApiBundle\Model;


use ApiBundle\Entity\Plik;
use ApiBundle\Exception\NiepelneDaneException;
use ApiBundle\Library\Helper\DaneWejsciowe\DaneUzytkownikaNaPoziomieDanychWejsciowych;
use ApiBundle\Library\Helper\DaneWejsciowe\DaneWejscioweUpload;
use ApiBundle\Library\Helper\DaneWejsciowe\EncjaPlikuNaPoziomieDanychWejsciowych;
use ApiBundle\Library\Helper\EncjaPliku;
use ApiBundle\Library\Plik\Generuj;
use ApiBundle\Utils\Data;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class PrzetworzDane
{
    /**
     * @var $container \Psr\Container\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @param Request $request
     * @return DaneWejscioweUpload
     * @throws NiepelneDaneException
     */
    public function przygotujDaneWejscioweUpload(Request $request)
    {
        try{
            $encja = new DaneWejscioweUpload(json_decode($request->getContent()), [
                'katalog_do_zapisu_plikow_tymczasowych' => $this->container->getParameter('katalog_do_zapisu_plikow_tymczasowych'),
                'katalog_do_zapisu_plikow' => $this->container->getParameter('katalog_do_zapisu_plikow')
            ]);
        }catch (\Exception $exception){
            throw new NiepelneDaneException();
        }

        return $encja;
    }


    /**
     * @param Request $request
     * @return array
     * @throws NiepelneDaneException
     */
    public function przygotujDaneWejscioweDownload(Request $request)
    {
        $daneWejsciowe = [
            'token' => $request->query->get('token', null),
            'uzytkownik' => [
                'login' => $request->query->get('login', null),
                'haslo' => $request->query->get('haslo', null)
            ],
            'id_zasobu' => $request->query->get('id_zasobu', null)
        ];

        if (array_search(null, $daneWejsciowe) !== false) {
            throw new NiepelneDaneException();
        }

        return $daneWejsciowe;
    }

    /**
     * @param $daneWejsciowe DaneWejscioweUpload
     * @return array
     */
    public function przetworzDaneWejsciowe($daneWejsciowe)
    {
        $odpowiedz = [];

        foreach ($daneWejsciowe->getKolekcjaPlikow() as $plik){
            $odpowiedz['pliki'][] = $plik;
        }

        $odpowiedz['uzytkownik'] = [
            'id' => null,
            'login' => $aDaneWejsciowe['uzytkownik']['login']
        ];

        return $odpowiedz;
    }

    /**
     * @param Plik $encjaPliku
     * @param EncjaPliku $noweDane
     * @param DaneUzytkownikaNaPoziomieDanychWejsciowych $uzytkownik
     * @return Plik
     */
    public static function uzupelnijEncjePliku(Plik $encjaPliku, EncjaPliku $noweDane, DaneUzytkownikaNaPoziomieDanychWejsciowych $uzytkownik)
    {
        $encjaPliku->setSciezka($noweDane->getSciezkaDoPlikuNaDysku());
        $encjaPliku->setNazwaZasobu($noweDane->getNowaNazwaPlikuZRozszerzeniem());
        $encjaPliku->setPierwotnaNazwa($noweDane->getPierwotnaNazwaPliku());
        $encjaPliku->setRozmiar($noweDane->getRozmiar());
        $encjaPliku->setDataDodania($noweDane->getDataDodania());
        $encjaPliku->setUzytkownikDodajacy($uzytkownik->getId());
        $encjaPliku->setMimeType($noweDane->getMimeType());
        $encjaPliku->setIdZasobu($noweDane->getIdZasobu());
        $encjaPliku->setCzyUsuniety(false);
        return $encjaPliku;
    }

    /**
     * @return string
     */
    private function generujUnikalnyIdentyfikator(): string
    {
        return (Uuid::uuid5(
            Uuid::NAMESPACE_DNS,
            md5(uniqid(rand(), true))
        ))->toString();
    }


    /**
     * @param $dane
     * @return array
     */
    public function pobierzIdWszystkichZasobowDlaTegoZadania(DaneWejscioweUpload $dane): array
    {
        $zasoby = [];

        /**
         * @var $plik EncjaPlikuNaPoziomieDanychWejsciowych
         */
        foreach ($dane->getKolekcjaPlikow() as $plik) {
            $zasoby[] = [
                'id_zasobu' => $plik->getEncjaPliku()->getIdZasobu(),
                'pierwotna_nazwa' => $plik->getEncjaPliku()->getPierwotnaNazwaPliku()
            ];
        }
        return $zasoby;
    }

    /**
     * @param $request
     * @return array
     * @throws NiepelneDaneException
     */
    public function przygotujDaneWejscioweDelete($request)
    {
        $daneWejsciowe = [
            'token' => $request->request->get('token', null),
            'uzytkownik' => [
                'login' => $request->request->get('login', null),
                'haslo' => $request->request->get('haslo', null)
            ],
            'id_zasobu' => $request->request->get('id_zasobu', null)
        ];

        if (array_search(null, $daneWejsciowe) !== false) {
            throw new NiepelneDaneException();
        }

        return $daneWejsciowe;
    }

    /**
     * @param $request
     * @return array
     * @throws NiepelneDaneException
     */
    public function przygotujDaneWejsciowePut($request)
    {
        $daneWejsciowe = [
            'token' => $request->request->get('token', null),
            'uzytkownik' => [
                'login' => $request->request->get('login', null),
                'haslo' => $request->request->get('haslo', null)
            ],
            'id_zasobu' => $request->request->get('id_zasobu', null)
        ];

        if (array_search(null, $daneWejsciowe) !== false) {
            throw new NiepelneDaneException();
        }

        $daneWejsciowe['elementy_do_zmiany'] = [
            'pierwotna_nazwa' => $request->request->get('pierwotna_nazwa', null),
            'czy_usuniety' => $request->request->get('czy_usuniety', null)
        ];

        if(empty(array_filter($daneWejsciowe['elementy_do_zmiany']))){
            throw new NiepelneDaneException();
        }

        return $daneWejsciowe;
    }

}