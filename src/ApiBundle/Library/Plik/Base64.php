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
use ApiBundle\Model\FizycznyPlik;

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

        $encjaPliku = (new EncjaPliku)
            ->nazwa()
                ->setPierwotnaNazwaPliku($pierwotnaNazwa)
                ->setNowaNazwaPlikuZRozszerzeniem($nowaNazwaPliku . '.' .$rozszerzenie)
                ->setNowaNazwaPlikuBezRozszerzenia($nowaNazwaPliku)
            ->wlasciwosc()
                ->setMimeType($mimeTypeIBase64[0])
                ->setRozszerzenie($rozszerzenie)
                ->setZawartosc($odkodowanaZawartosc)
                ->setDataDodania(new \DateTime())
                ->setIdZasobu(Generuj::UnikalnaNazwe())
                ->setRozmiar(0)
                ->setZapisanyPlikTymczasowy(false)
                ->setZapisanyPlikDocelowy(false)
            ->lokalizacja()
                ->setKatalogZapisuDocelowego($katalogDoZapisu)
                ->setKatalogZapisuTymczasowego($katalogZapisuTymczasowego)
                ->setSciezkaDoPlikuDocelowego($sciezkaZapisu)
                ->setSciezkaDoPlikuTymczasowego($sciezkaZapisuTymczasowego)
            ->daneUzytkownikaDodajcego()
                ->setIdUzytkownikaDodajacego($daneUzytkownikaNaPoziomieDanychWejsciowych->getId())
                ->setLoginUzytkownikaDodajacego($daneUzytkownikaNaPoziomieDanychWejsciowych->getLogin());

        $encjaPliku = (new FizycznyPlik())->zapiszPlikTymczasowy($encjaPliku);

        $encjaPliku->wlasciwosc()
            ->setRozmiar(filesize($sciezkaZapisu));

        return $encjaPliku;
    }
}