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

    public function __construct($maksymalnyRozmiarPliku = 0)
    {
        $this->maksymalnyRozmiarPliku = $maksymalnyRozmiarPliku;
    }


    /**
     * @param $daneWejsciowe DaneWejscioweUpload
     * @throws BladZapisuPlikuNaDyskuException
     * @internal param UploadedFile $plik
     */
    public function zapiszPlikNaDysku($daneWejsciowe)
    {
        try {
            $i = 0;
            /**
             * @var $plik EncjaPlikuNaPoziomieDanychWejsciowych
             */
            foreach ($daneWejsciowe->getKolekcjaPlikow() as $plik){
                $this->zapiszPlik($plik->getEncjaPliku());
                $i++;
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
     * @throws RozmiarPlikuJestZbytDuzyException
     */
    private function zapiszPlik(EncjaPliku $plik): void
    {
        if ((int)$plik->getRozmiar() / 1024 / 1024 > (int)$this->maksymalnyRozmiarPliku) {
            throw new RozmiarPlikuJestZbytDuzyException;
        }
        $katalog = $this->przygotujKatalogDoZapisu($plik->getKatalogZapisu());

        (new Plik())->zapisz($plik->getSciezkaDoPlikuNaDysku(), $plik->getZawartosc());
    }

    public function czyPlikIstniejeNaDysku($sciezkaDoZasobu)
    {
        return file_exists($sciezkaDoZasobu);
    }
}