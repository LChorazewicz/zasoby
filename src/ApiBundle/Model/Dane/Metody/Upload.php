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
use ApiBundle\Exception\RozmiarKolekcjiPlikowJestZbytDuzyException;
use ApiBundle\Helper\EncjaPliku;
use ApiBundle\Library\Plik;
use ApiBundle\Model\Dane\DaneAbstract;
use ApiBundle\Model\Dane\DaneInterface;
use ApiBundle\Model\Dane\Metody\Interfaces\UploadInterface;
use ApiBundle\RabbitMQ\Kolejka;
use ApiBundle\RabbitMQ\Producer\EmailProducer;
use ApiBundle\RabbitMQ\Producer\Helper\WysylkaEmailowHelper;
use ApiBundle\Services\KontenerParametrow;
use ApiBundle\Utils\Data;
use ApiBundle\Library\WarunkiBrzegowe\Plik as WarunkiBrzegowe;

class Upload extends DaneAbstract implements DaneInterface, UploadInterface
{
    private $kolekcjaPlikow;

    /**
     * Upload constructor.
     * @param Uzytkownik $uzytkownik
     * @param $daneWejsciowe
     * @param $nazwaMetodyApi
     * @param KontenerParametrow $kontenerParametrow
     */
    public function __construct(Uzytkownik $uzytkownik, \stdClass $daneWejsciowe, $nazwaMetodyApi, KontenerParametrow $kontenerParametrow)
    {
        parent::__construct($uzytkownik, $daneWejsciowe, $nazwaMetodyApi, $kontenerParametrow);
        $this->setKolekcjaPlikow($daneWejsciowe->pliki, $this->pobierzDaneUzytkownika());
    }

    /**
     * @param $kolekcjaPlikow
     * @param $uzytkownikaDodajacy
     * @throws PustaKolekcjaException
     * @throws RozmiarKolekcjiPlikowJestZbytDuzyException
     */
    private function setKolekcjaPlikow($kolekcjaPlikow, $uzytkownikaDodajacy)
    {
        $kolekcja = [];

        foreach ($kolekcjaPlikow as $plik) {
            $obiekt = (new Plik())->konwertujBase64DoEncjiPliku(
                $plik->base64,
                $plik->pierwotna_nazwa,
                $this->zmienna('katalog_do_zapisu_plikow_tymczasowych'),
                $this->zmienna('katalog_do_zapisu_plikow') . Data::pobierzDzisiejszaDateWFormacieKrotkim() . '/',
                $uzytkownikaDodajacy);

            if (is_null($obiekt)) {
                continue;
            }
            $kolekcja[] = $obiekt;
        }

        if (empty($kolekcja)) {
            throw new PustaKolekcjaException();
        }

        if(WarunkiBrzegowe::rozmiarKolekcjiPlikowJestWiekszyNiz($kolekcja, $this->kontenerParametrow()->pobierz('maksymalny_rozmiar_kolekcji_plikow_w_megabajtach'))){
            throw new RozmiarKolekcjiPlikowJestZbytDuzyException();
        }

        $this->kolekcjaPlikow = $kolekcja;
    }

    /**
     * @return UploadInterface
     */
    public function pobierz(): UploadInterface
    {
        return $this;
    }

    public function pobierzKolekcjeEncji(): array
    {
        return $this->kolekcjaPlikow;
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

    /**
     * @param UploadInterface $upload
     * @return array
     */
    public function pobierzDaneWszystkichZapisanychZasobow(UploadInterface $upload)
    {
        $zasoby = [];
        /**
         * @var $plik EncjaPliku
         */
        foreach ($upload->pobierzKolekcjePlikow() as $plik) {
            $zasoby[] = [
                'id_zasobu' => $plik->getIdZasobu(),
                'pierwotna_nazwa' => $plik->getPierwotnaNazwaPliku()
            ];
        }

        return $zasoby;
    }

    public function wyslijEmailaPoZakonczeniuUploadu(Kolejka $kolejka, string $wiadomosc)
    {
        (new EmailProducer(
            (new WysylkaEmailowHelper())
                ->setNadawca($this->kontenerParametrow()->pobierz('nadawca_emailow'))
                ->setOdbiorca($this->kontenerParametrow()->pobierz('odbiorca_emailow'))
                ->setTemat("ApiZasoby - Upload plików")
                ->setWiadomosc($wiadomosc)
                ->setKolejka($kolejka)
        ))->wyslij();
    }
}