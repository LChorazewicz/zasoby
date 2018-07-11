<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.06.18
 * Time: 17:11
 */

namespace ApiBundle\Model;


use ApiBundle\Exception\BladZapisuPlikuNaDyskuException;
use ApiBundle\Exception\RozmiarPlikuJestZbytDuzyException;
use ApiBundle\Library\Helper\DaneWejsciowe\DaneWejscioweUpload;
use ApiBundle\Library\Helper\DaneWejsciowe\EncjaPlikuNaPoziomieDanychWejsciowych;
use ApiBundle\Library\Helper\EncjaPliku;
use ApiBundle\Library\Plik\Plik;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FizycznyPlik
{
    private $maksymalnyRozmiarPliku;

    /**
     * FizycznyPlik constructor.
     * @param int $maksymalnyRozmiarPliku
     */
    public function __construct($maksymalnyRozmiarPliku = 0)
    {
        $this->maksymalnyRozmiarPliku = $maksymalnyRozmiarPliku;
    }


    /**
     * @param $daneWejsciowe DaneWejscioweUpload
     * @throws BladZapisuPlikuNaDyskuException
     */
    public function zapiszPlikiDoceloweNaDysku($daneWejsciowe)
    {
        try {
            $i = 0;
            /**
             * @var $plik EncjaPlikuNaPoziomieDanychWejsciowych
             */
            foreach ($daneWejsciowe->getKolekcjaPlikow() as $plik){
                $this->zapiszPlikDocelowy($plik->getEncjaPliku());$i++;
            }

        } catch (FileException $exception) {
            throw new BladZapisuPlikuNaDyskuException;
        }
    }

    /**
     * @param $nazwaKatalogu
     * @return string
     */
    private function przygotujKatalogDoZapisu($nazwaKatalogu)
    {
        if (!is_dir($nazwaKatalogu)) {
            mkdir($nazwaKatalogu, 0777, true);
        }
        return $nazwaKatalogu;
    }

    /**
     * @param EncjaPliku $plik
     * @param bool $docelowy
     * @return EncjaPliku
     * @throws RozmiarPlikuJestZbytDuzyException
     */
    private function zapiszPlik(EncjaPliku $plik, bool $docelowy): EncjaPliku
    {
        if ((int)$plik->getRozmiar() / 1024 / 1024 > (int)$this->maksymalnyRozmiarPliku) {
            throw new RozmiarPlikuJestZbytDuzyException;
        }

        $oPlik = new Plik();

        if($docelowy){
            $sciezka = $plik->lokalizacja()->getSciezkaDoPlikuDocelowego();
            $this->przygotujKatalogDoZapisu($plik->lokalizacja()->getKatalogZapisuDocelowego());
            if($plik->wlasciwosc()->isZapisanyPlikTymczasowy()){
                $oPlik->usun($plik->lokalizacja()->getSciezkaDoPlikuTymczasowego());
                $plik->wlasciwosc()->setZapisanyPlikTymczasowy(false);
                $plik->wlasciwosc()->setZapisanyPlikDocelowy(true);
            }
        }else{
            $sciezka = $plik->lokalizacja()->getSciezkaDoPlikuTymczasowego();
            $this->przygotujKatalogDoZapisu($plik->lokalizacja()->getKatalogZapisuTymczasowego());
            $plik->wlasciwosc()->setZapisanyPlikTymczasowy(true);
            $plik->wlasciwosc()->setZapisanyPlikDocelowy(false);
        }
        $oPlik->zapisz($sciezka, $plik->wlasciwosc()->getZawartosc());
        return $plik;
    }

    public function zapiszPlikDocelowy(EncjaPliku $encjaPliku)
    {
       return $this->zapiszPlik($encjaPliku, true);
    }

    public function zapiszPlikTymczasowy(EncjaPliku $encjaPliku)
    {
        return $this->zapiszPlik($encjaPliku, false);
    }

    public function czyPlikIstniejeNaDysku($sciezkaDoZasobu)
    {
        return file_exists($sciezkaDoZasobu);
    }
}