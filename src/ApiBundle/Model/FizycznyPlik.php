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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FizycznyPlik
{
    private $maksymalnyRozmiarPliku;

    public function __construct($maksymalnyRozmiarPliku)
    {
        $this->maksymalnyRozmiarPliku = $maksymalnyRozmiarPliku;
    }


    /**
     * @param UploadedFile $plik
     * @param $sciezka
     * @param $nazwa
     * @throws BladZapisuPlikuNaDyskuException
     * @throws RozmiarPlikuJestZbytDuzyException
     */
    public function zapiszPlikNaDysku(UploadedFile $plik, $sciezka, $nazwa)
    {
        try {
            /**
             * File getSize zwraca rozmiar pliku w kilobajtach
             */
            if ($plik->getSize() / 1024 / 1024 > (int)$this->maksymalnyRozmiarPliku) {
                throw new RozmiarPlikuJestZbytDuzyException;
            }
            $katalog = $this->przygotujKatalogDoZapisu($sciezka);

            $plik->move($katalog, $nazwa);

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
}