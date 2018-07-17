<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.07.18
 * Time: 20:39
 */

namespace ApiBundle\Model\Dane\Metody;


use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Exception\PustaKolekcjaException;
use ApiBundle\Helper\EncjaPliku;
use ApiBundle\Library\Plik;
use ApiBundle\Model\Dane\DaneAbstract;
use ApiBundle\Model\Dane\DaneInterface;
use ApiBundle\Services\KontenerParametrow;
use ApiBundle\Utils\Data;

class Upload extends DaneAbstract implements DaneInterface, UploadInterface
{
    /**
     * @var EncjaPliku $kolekcjaPlikow | []
     */
    private $kolekcjaPlikow;

    public function __construct(Uzytkownik $uzytkownik, $daneWejsciowe, $nazwaMetodyApi, KontenerParametrow $kontenerParametrow)
    {
        parent::__construct($uzytkownik, '', $nazwaMetodyApi, $kontenerParametrow);
        $this->setKolekcjaPlikow($daneWejsciowe->pliki, $this->pobierzDaneUzytkownika());
    }

    /**
     * @param $kolekcjaPlikow
     * @param $uzytkownikaDodajacy
     * @throws PustaKolekcjaException
     */
    private function setKolekcjaPlikow($kolekcjaPlikow, $uzytkownikaDodajacy)
    {
        $kolekcja = [];

        foreach ($kolekcjaPlikow as $plik){

            $obiekt = (new Plik())->konwertujBase64DoEncjiPliku(
                $plik->base64,
                $plik->pierwotna_nazwa,
                $this->zmienna('katalog_do_zapisu_plikow_tymczasowych'),
                $this->zmienna('katalog_do_zapisu_plikow') . Data::pobierzDzisiejszaDateWFormacieKrotkim() . '/',
                $uzytkownikaDodajacy);

            if(is_null($obiekt)){
                continue;
            }
            $kolekcja[] = $obiekt;
        }

        if(empty($kolekcja)){
            throw new PustaKolekcjaException();
        }

        $this->kolekcjaPlikow = $kolekcja;
    }

    /**
     * @return UploadInterface
     */
    public function pobierz() : UploadInterface
    {
        return $this;
    }

    public function pobierzKolekcjePlikow()
    {
        return $this->kolekcjaPlikow;
    }

    /**
     * @return Uzytkownik
     */
    public function pobierzDaneUzytkownika()
    {
        return $this->daneUzytkownika();
    }
}