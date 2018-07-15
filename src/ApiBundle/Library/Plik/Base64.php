<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 07.07.18
 * Time: 17:18
 */

namespace ApiBundle\Library\Plik;


use ApiBundle\Exception\WarunkiBrzegoweNieZostalySpelnioneException;
use ApiBundle\Library\Generuj;
use ApiBundle\Library\Helper\DaneWejsciowe\DaneUzytkownikaNaPoziomieDanychWejsciowych;
use ApiBundle\Library\Helper\EncjaPliku;
use ApiBundle\Library\WarunkiBrzegowe\Plik;
use ApiBundle\Model\FizycznyPlik;

class Base64
{
    /**
     * @param $base64
     * @param $nazwa
     * @param $katalogTymczasowy
     * @param $katalogDocelowy
     * @param DaneUzytkownikaNaPoziomieDanychWejsciowych $daneUzytkownikaNaPoziomieDanychWejsciowych
     * @return EncjaPliku
     */
    public function konwertujBase64DoEncjiPliku($base64, $nazwa, $katalogTymczasowy, $katalogDocelowy, DaneUzytkownikaNaPoziomieDanychWejsciowych $daneUzytkownikaNaPoziomieDanychWejsciowych)
    {
        return $this->zwrocEncjePlikuZBase64($base64, $nazwa, $katalogTymczasowy, $katalogDocelowy, $daneUzytkownikaNaPoziomieDanychWejsciowych);
    }

    /**
     * @param string $base64
     * @param string $pierwotnaNazwa
     * @param string $katalogTymczasowy
     * @param string $katalogDocelowy
     * @param DaneUzytkownikaNaPoziomieDanychWejsciowych $daneUzytkownikaNaPoziomieDanychWejsciowych
     * @return EncjaPliku
     * @throws WarunkiBrzegoweNieZostalySpelnioneException
     */
    private function zwrocEncjePlikuZBase64(string $base64, string $pierwotnaNazwa, string $katalogTymczasowy, string $katalogDocelowy, DaneUzytkownikaNaPoziomieDanychWejsciowych $daneUzytkownikaNaPoziomieDanychWejsciowych)
    {
        $daneBase64 = (new \ApiBundle\Library\Helper\DaneWejsciowe\Base64($base64, $pierwotnaNazwa));

        $nowaNazwaPliku = Generuj::UnikalnaNazwe();

        $sciezkaZapisuTymczasowego = $katalogTymczasowy . $nowaNazwaPliku . '.' . $daneBase64->getRozszerzenie();
        $sciezkaZapisuDocelowego = $katalogDocelowy . $nowaNazwaPliku . '.' . $daneBase64->getRozszerzenie();

        $encjaPliku = (new EncjaPliku)
            ->nazwa()
                ->setPierwotnaNazwaPliku($daneBase64->getPierwotnaNazwa())
                ->setNowaNazwaPlikuZRozszerzeniem($nowaNazwaPliku . '.' .$daneBase64->getRozszerzenie())
                ->setNowaNazwaPlikuBezRozszerzenia($nowaNazwaPliku)
            ->wlasciwosc()
                ->setMimeType($daneBase64->getMimeType())
                ->setRozszerzenie($daneBase64->getRozszerzenie())
                ->setZawartosc($daneBase64->getOdkodowanaZawartosc())
                ->setDataDodania(new \DateTime())
                ->setIdZasobu(Generuj::UnikalnaNazwe())
                ->setRozmiar(0)
                ->setZapisanyPlikTymczasowy(false)
                ->setZapisanyPlikDocelowy(false)
            ->lokalizacja()
                ->setKatalogZapisuDocelowego($katalogDocelowy)
                ->setKatalogZapisuTymczasowego($katalogTymczasowy)
                ->setSciezkaDoPlikuDocelowego($sciezkaZapisuDocelowego)
                ->setSciezkaDoPlikuTymczasowego($sciezkaZapisuTymczasowego)
            ->daneUzytkownikaDodajcego()
                ->setIdUzytkownikaDodajacego($daneUzytkownikaNaPoziomieDanychWejsciowych->getId())
                ->setLoginUzytkownikaDodajacego($daneUzytkownikaNaPoziomieDanychWejsciowych->getLogin());

        if(!Plik::czyEncjaPlikuKwalifikujeSieDoZapisu($encjaPliku)){
            return null;
        }

        $encjaPliku = (new FizycznyPlik())->zapiszPlikTymczasowy($encjaPliku);

        $encjaPliku->wlasciwosc()
            ->setRozmiar(filesize($sciezkaZapisuTymczasowego));

        return $encjaPliku;
    }
}