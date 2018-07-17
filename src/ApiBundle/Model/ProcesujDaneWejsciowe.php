<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.06.18
 * Time: 16:58
 */

namespace ApiBundle\Model;


use ApiBundle\Entity\Plik;
use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Exception\NiepelneDaneException;
use ApiBundle\Helper\EncjaPliku;
use ApiBundle\Library\Helper\DaneWejsciowe\DaneWejscioweAbstractPatch;
use ApiBundle\Model\Dane\Metody\Upload;
use ApiBundle\Model\Dane\Metody\UploadInterface;
use ApiBundle\Model\DaneWejsciowe\DaneWejscioweInterface;
use ApiBundle\Model\DaneWejsciowe\Metody\Delete;
use ApiBundle\Model\DaneWejsciowe\Metody\Download;
use ApiBundle\Model\DaneWejsciowe\Metody\Put;
use ApiBundle\Services\KontenerParametrow;
use Symfony\Component\HttpFoundation\Request;

class ProcesujDaneWejsciowe
{

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
     * @param EncjaPliku $danePliku
     * @param Uzytkownik $uzytkownik
     * @return Plik
     */
    public static function uzupelnijEncjePliku(EncjaPliku $danePliku, Uzytkownik $uzytkownik)
    {
        $encjaPliku = new Plik();
        $encjaPliku->setSciezka($danePliku->getSciezkaDoPlikuDocelowego());
        $encjaPliku->setNazwaZasobu($danePliku->getNowaNazwaPlikuZRozszerzeniem());
        $encjaPliku->setPierwotnaNazwa($danePliku->getPierwotnaNazwaPliku());
        $encjaPliku->setRozmiar($danePliku->getRozmiar());
        $encjaPliku->setDataDodania($danePliku->getDataDodania());
        $encjaPliku->setUzytkownikDodajacy($uzytkownik->getId());
        $encjaPliku->setMimeType($danePliku->getMimeType());
        $encjaPliku->setIdZasobu($danePliku->getIdZasobu());
        $encjaPliku->setCzyUsuniety(false);
        return $encjaPliku;
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

        if (empty(array_filter($daneWejsciowe['elementy_do_zmiany']))) {
            throw new NiepelneDaneException();
        }

        return $daneWejsciowe;
    }

    /**
     * @param Request $request
     * @return DaneWejscioweAbstractPatch
     * @throws NiepelneDaneException
     */
    public function przygotujDaneWejsciowePatch($request)
    {
        try {
            $encja = new DaneWejscioweAbstractPatch(json_decode($request->getContent()), $this->kontenerParametrow);
        } catch (NiepelneDaneException $exception) {
            throw new NiepelneDaneException();
        }

        return $encja;
    }


    public function przygotujDane(DaneWejscioweInterface $daneWejsciowe, KontenerParametrow $kontenerParametrow)
    {
        $przygotowaneDaneWejsciowe = null;

        switch ($daneWejsciowe::getNazwaMetodyApi()) {
            case 'Upload': {
                $przygotowaneDaneWejsciowe = (
                    new Upload($daneWejsciowe->getDaneUzytkownika(), $daneWejsciowe->getDaneWejsciowe(), $daneWejsciowe::getNazwaMetodyApi(), $kontenerParametrow)
                )->pobierz();
                break;
            }
            case Download::class: {
            }
            case Put::class: {
            }
            case Delete::class: {
            }
        }

        return $przygotowaneDaneWejsciowe;
    }

}