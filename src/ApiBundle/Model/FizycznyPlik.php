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
use ApiBundle\Helper\EncjaPliku;
use ApiBundle\Library\Plik;
use ApiBundle\Model\Dane\Metody\UploadInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
     * @param UploadInterface $upload
     * @throws BladZapisuPlikuNaDyskuException
     */
    public function zapiszPlikiDoceloweNaDysku(UploadInterface $upload)
    {
        try {
            foreach ($upload->pobierzKolekcjePlikow() as $plik){
                $this->zapiszPlikDocelowy($plik);
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
}