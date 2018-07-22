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
use ApiBundle\Helper\EncjaPliku;
use ApiBundle\Model\Dane\Metody\StrumienSzkic;
use ApiBundle\Model\Dane\Metody\Upload;
use ApiBundle\Model\DaneWejsciowe\DaneWejscioweInterface;
use ApiBundle\Services\KontenerParametrow;

class ProcesujDaneWejsciowe
{

    /**
     * @param EncjaPliku $danePliku
     * @param Uzytkownik $uzytkownik
     * @return Plik
     */
    public static function uzupelnijEncjePliku(EncjaPliku $danePliku, Uzytkownik $uzytkownik)
    {
        $encjaPliku = new Plik();
        $encjaPliku->setSciezka($danePliku->getSciezkaDoPlikuDocelowego())
            ->setNazwaZasobu($danePliku->getNowaNazwaPlikuZRozszerzeniem())
            ->setPierwotnaNazwa($danePliku->getPierwotnaNazwaPliku())
            ->setRozmiar($danePliku->getRozmiar())
            ->setDataDodania($danePliku->getDataDodania())
            ->setUzytkownikDodajacy($uzytkownik->getId())
            ->setMimeType($danePliku->getMimeType())
            ->setIdZasobu($danePliku->getIdZasobu())
            ->setCzyUsuniety(false)
            ->setSzkic(false);
        return $encjaPliku;
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
            case 'StrumienSzkic':{
                $przygotowaneDaneWejsciowe = (
                    new StrumienSzkic($daneWejsciowe->getDaneUzytkownika(), $daneWejsciowe->getDaneWejsciowe(), $daneWejsciowe::getNazwaMetodyApi(), $kontenerParametrow)
                )->pobierz();
                break;
            }
        }

        return $przygotowaneDaneWejsciowe;
    }

}