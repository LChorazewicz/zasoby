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
     * @param $daneWejsciowe
     * @param $noweDane
     * @throws BladZapisuPlikuNaDyskuException
     * @internal param UploadedFile $plik
     */
    public function zapiszPlikNaDysku($daneWejsciowe, $noweDane)
    {
        try {
            $i = 0;
            foreach ($daneWejsciowe['pliki'] as $plik){
                $this->zapiszPlik($plik, $noweDane['pliki'][$i]['sciezka_do_katalogu_na_dysku'], $noweDane['pliki'][$i]['nazwa']['nowa_z_rozszerzeniem']);
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
     * @param UploadedFile $plik
     * @param $sciezka
     * @param $nazwa
     * @throws RozmiarPlikuJestZbytDuzyException
     */
    private function zapiszPlik(UploadedFile $plik, $sciezka, $nazwa): void
    {
        if ($plik->getSize() / 1024 / 1024 > (int)$this->maksymalnyRozmiarPliku) {
            throw new RozmiarPlikuJestZbytDuzyException;
        }
        $katalog = $this->przygotujKatalogDoZapisu($sciezka);

        $plik->move($katalog, $nazwa);
    }

    public function czyPlikIstniejeNaDysku($sciezkaDoZasobu)
    {
        return file_exists($sciezkaDoZasobu);
    }
}