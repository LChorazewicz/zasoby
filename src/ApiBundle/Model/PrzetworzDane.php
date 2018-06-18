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
            'token' => $request->request->get('form', null)['token'],
            'uzytkownik' => [
                'login' => $request->request->get('form', null)['login'],
                'haslo' => $request->request->get('form', null)['haslo']
            ],
            'plik' => $request->files->get('form', null)['plik'][0]
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
            'token' => $request->query->get('form', null)['token'],
            'uzytkownik' => [
                'login' => $request->query->get('form', null)['login'],
                'haslo' => $request->query->get('form', null)['haslo']
            ],
            'id_zasobu' => $request->query->get('form', null)['id_zasobu']
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
        /**
         * @var $plik UploadedFile
         */
        $plik = $aDaneWejsciowe['plik'];

        $nazwaZasobuNaDysku = (Uuid::uuid5(
            Uuid::NAMESPACE_DNS,
            md5(uniqid(rand(), true))
        ))->toString();

        $idZasobu = (Uuid::uuid5(
            Uuid::NAMESPACE_DNS,
            md5(uniqid(rand(), true))
        ))->toString();

        $nowaNazwaPlikuNaDysku = $nazwaZasobuNaDysku . '.' . $plik->getClientOriginalExtension();

        $katalogDoZapisu = $this->container->getParameter('katalog_do_zapisu_plikow') .
            Data::pobierzDzisiejszaDateWFormacieKrotkim() . '/';

        return [
            'plik' => [
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
            ],
            'uzytkownik' => [
                'id' => null,
                'login' => $aDaneWejsciowe['uzytkownik']['login']
            ]
        ];
    }

    public function uzupelnijEncjePliku(Plik $encjaPliku, $noweDane)
    {
        $encjaPliku->setSciezka($noweDane['plik']['sciezka_do_zasobu_na_dysku']);
        $encjaPliku->setNazwaZasobu($noweDane['plik']['nazwa']['nowa_z_rozszerzeniem']);
        $encjaPliku->setPierwotnaNazwa($noweDane['plik']['nazwa']['pierwotna_z_rozszerzeniem']);
        $encjaPliku->setRozmiar($noweDane['plik']['rozmiar']);
        $encjaPliku->setDataDodania($noweDane['plik']['data_dodania']);
        $encjaPliku->setUzytkownikDodajacy($noweDane['uzytkownik']['id']);
        $encjaPliku->setMimeType($noweDane['plik']['mime_type']);
        $encjaPliku->setIdZasobu($noweDane['plik']['id_zasobu']);
        $encjaPliku->setCzyUsuniety(false);
        return $encjaPliku;
    }

}