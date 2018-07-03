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
     * @return array
     * @throws NiepelneDaneException
     */
    public function przygotujDaneWejscioweUpload(Request $request)
    {
        $daneWejsciowe = [
            'token' => $request->request->get('token', null),
            'uzytkownik' => [
                'login' => $request->request->get('login', null),
                'haslo' => $request->request->get('haslo', null)
            ],
            'pliki' => $request->files->get('pliki', null)
        ];

        if (array_search(null, $daneWejsciowe) !== false) {
            throw new NiepelneDaneException();
        }

        return $daneWejsciowe;
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
     * @param $aDaneWejsciowe
     * @return array
     */
    public function przetworzDaneWejsciowe($aDaneWejsciowe)
    {
        $odpowiedz = [];

        /**
         * @var $plik UploadedFile
         */
        foreach ($aDaneWejsciowe['pliki'] as $plik){
            $idZasobu = $this->generujUnikalnyIdentyfikator();
            $nazwaZasobuNaDysku = $this->generujUnikalnyIdentyfikator();

            $nowaNazwaPlikuNaDysku = $nazwaZasobuNaDysku . '.' . $plik->getClientOriginalExtension();
            $katalogDoZapisu = $this->container->getParameter('katalog_do_zapisu_plikow') .
                Data::pobierzDzisiejszaDateWFormacieKrotkim() . '/';

            $odpowiedz['pliki'][] = [
                'nazwa' => [
                    'pierwotna_z_rozszerzeniem' => $plik->getClientOriginalName(),
                    'nowa_z_rozszerzeniem' => $nowaNazwaPlikuNaDysku,
                    'nowa_bez_rozszerzenia' => $nazwaZasobuNaDysku
                ],
                'id_zasobu' =>  $idZasobu,
                'rozmiar' => $plik->getClientSize(),
                'sciezka_do_katalogu_na_dysku' => $katalogDoZapisu,
                'sciezka_do_zasobu_na_dysku' => $katalogDoZapisu . $nowaNazwaPlikuNaDysku,
                'data_dodania' => new \DateTime(),
                'mime_type' => $plik->getClientMimeType()
            ];
        }

        $odpowiedz['uzytkownik'] = [
            'id' => null,
            'login' => $aDaneWejsciowe['uzytkownik']['login']
        ];

        return $odpowiedz;
    }

    public function uzupelnijEncjePliku(Plik $encjaPliku, $noweDane, $uzytkownik)
    {
        $encjaPliku->setSciezka($noweDane['sciezka_do_zasobu_na_dysku']);
        $encjaPliku->setNazwaZasobu($noweDane['nazwa']['nowa_z_rozszerzeniem']);
        $encjaPliku->setPierwotnaNazwa($noweDane['nazwa']['pierwotna_z_rozszerzeniem']);
        $encjaPliku->setRozmiar($noweDane['rozmiar']);
        $encjaPliku->setDataDodania($noweDane['data_dodania']);
        $encjaPliku->setUzytkownikDodajacy($uzytkownik['id']);
        $encjaPliku->setMimeType($noweDane['mime_type']);
        $encjaPliku->setIdZasobu($noweDane['id_zasobu']);
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
     * @param $noweDane
     * @return array
     */
    public function pobierzIdWszystkichZasobowDlaTegoZadania($noweDane): array
    {
        $zasoby = [];
        foreach ($noweDane['pliki'] as $plik) {
            $zasoby[] = [
                'id_zasobu' => $plik['id_zasobu'],
                'pierwotna_nazwa' => $plik['nazwa']['pierwotna_z_rozszerzeniem']
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