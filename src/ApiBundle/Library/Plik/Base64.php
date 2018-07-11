<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 07.07.18
 * Time: 17:18
 */

namespace ApiBundle\Library\Plik;


use ApiBundle\Library\Generuj;
use ApiBundle\Library\Helper\DaneWejsciowe\DaneUzytkownikaNaPoziomieDanychWejsciowych;
use ApiBundle\Library\Helper\EncjaPliku;

class Base64
{
    /**
     * @param $base64
     * @param $nazwa
     * @param $katalogZapisuTymczasowego
     * @param $katalogZapisu
     * @param DaneUzytkownikaNaPoziomieDanychWejsciowych $daneUzytkownikaNaPoziomieDanychWejsciowych
     * @return EncjaPliku
     */
    public function konwertujBase64DoEncjiPliku($base64, $nazwa, $katalogZapisuTymczasowego, $katalogZapisu, DaneUzytkownikaNaPoziomieDanychWejsciowych $daneUzytkownikaNaPoziomieDanychWejsciowych)
    {
        return $this->zwrocEncjePlikuZBase64($base64, $nazwa, $katalogZapisuTymczasowego, $katalogZapisu, $daneUzytkownikaNaPoziomieDanychWejsciowych);
    }

    /**
     * @param string $base64
     * @param string $pierwotnaNazwa
     * @param string $katalogZapisuTymczasowego
     * @param string $katalogDoZapisu
     * @param DaneUzytkownikaNaPoziomieDanychWejsciowych $daneUzytkownikaNaPoziomieDanychWejsciowych
     * @return EncjaPliku
     */
    private function zwrocEncjePlikuZBase64(string $base64, string $pierwotnaNazwa, string $katalogZapisuTymczasowego, string $katalogDoZapisu, DaneUzytkownikaNaPoziomieDanychWejsciowych $daneUzytkownikaNaPoziomieDanychWejsciowych)
    {
        $base64 = str_replace('data:', '', $base64);
        $base64 = str_replace('base64,', '', $base64);

        $mimeTypeIBase64 = explode(';', $base64);

        $odkodowanaZawartosc = base64_decode($mimeTypeIBase64[1]);

        $nowaNazwaPliku = Generuj::UnikalnaNazwe();
        $rozszerzenie = explode('.', $pierwotnaNazwa)[1];

        $sciezkaZapisuTymczasowego = $katalogZapisuTymczasowego . $nowaNazwaPliku . '.' . $rozszerzenie;
        $sciezkaZapisu = $katalogZapisuTymczasowego . $nowaNazwaPliku . '.' . $rozszerzenie;

        (new Plik())->zapisz($sciezkaZapisu, $odkodowanaZawartosc);

        return (new EncjaPliku)
            ->setPierwotnaNazwaPliku($pierwotnaNazwa)
            ->setMimeType($mimeTypeIBase64[0])
            ->setZawartosc($odkodowanaZawartosc)
            ->setNowaNazwaPlikuZRozszerzeniem($nowaNazwaPliku . '.' .$rozszerzenie)
            ->setNowaNazwaPlikuBezRozszerzenia($nowaNazwaPliku)
            ->setDataDodania(new \DateTime())
            ->setKatalogZapisu($katalogDoZapisu)
            ->setKatalogZapisuTymczasowego($katalogZapisuTymczasowego)
            ->setIdZasobu(Generuj::UnikalnaNazwe())
            ->setRozmiar(filesize($sciezkaZapisu))
            ->setRozszerzenie($rozszerzenie)
            ->setSciezkaDoPlikuNaDysku($sciezkaZapisu)
            ->setSciezkaDoZapisuPlikuTymczasowego($sciezkaZapisuTymczasowego)
            ->setIdUzytkownikaDodajacego($daneUzytkownikaNaPoziomieDanychWejsciowych->getId())
            ->setLoginUzytkownikaDodajacego($daneUzytkownikaNaPoziomieDanychWejsciowych->getLogin());
    }
}