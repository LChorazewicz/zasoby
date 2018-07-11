<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 07.07.18
 * Time: 18:24
 */

namespace ApiBundle\Library\Plik;


use ApiBundle\Library\Helper\EncjaPliku;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Plik
{
    /**
     * @param $lokalizacja
     * @param $zawartoscPliku
     * @return false|int
     */
    public function zapisz($lokalizacja, $zawartoscPliku)
    {
        return file_put_contents($lokalizacja, $zawartoscPliku);
    }

    public function przedstawEncjePlikuWPostaciObiektuUploadedFile(EncjaPliku $encjaPliku)
    {
        $this->zapisz($encjaPliku->getLokalizacja(), $encjaPliku->getZawartosc());

        return new UploadedFile(
            $encjaPliku->getLokalizacja(),
            $encjaPliku->getPierwotnaNazwaPliku(),
            $encjaPliku->getMimeType(),
            filesize($encjaPliku->getLokalizacja()),
            null, true
        );
    }
}