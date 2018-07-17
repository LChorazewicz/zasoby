<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 07.07.18
 * Time: 18:24
 */

namespace ApiBundle\Library;


use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Helper\EncjaPliku;
use ApiBundle\Library\WarunkiBrzegowe\Plik as WalidacjaPliku;
use ApiBundle\Model\FizycznyPlik;
use ApiBundle\Helper\DaneWejsciowe\Base64;

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

    public function usun($getSciezkaDoZapisuPlikuTymczasowego)
    {
        return unlink($getSciezkaDoZapisuPlikuTymczasowego);
    }

    public function przenies($lokalizacjaObecna, $lokalizacjaDocelowa){
        return rename($lokalizacjaObecna, $lokalizacjaDocelowa);
    }

    public function zakonczPrzetwarzaniePliku()
    {

    }

    public function czyPlikIstniejeNaDysku($sciezkaDoZasobu)
    {
        return file_exists($sciezkaDoZasobu);
    }

    /**
     * @param EncjaPliku $kolekcjaPlikow
     */
    public function zapiszKolekcjePlikow($kolekcjaPlikow)
    {

    }

    /**
     * @param $base64
     * @param $nazwa
     * @param $katalogTymczasowy
     * @param $katalogDocelowy
     * @param Uzytkownik $daneUzytkownikaNaPoziomieDanychWejsciowych
     * @return EncjaPliku
     */
    public function konwertujBase64DoEncjiPliku($base64, $nazwa, $katalogTymczasowy, $katalogDocelowy, Uzytkownik $daneUzytkownikaNaPoziomieDanychWejsciowych)
    {
        $daneBase64 = (new Base64($base64, $nazwa));

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

        if(!WalidacjaPliku::czyEncjaPlikuKwalifikujeSieDoZapisu($encjaPliku)){
            return null;
        }

        $encjaPliku = (new FizycznyPlik())->zapiszPlikTymczasowy($encjaPliku);

        $encjaPliku->wlasciwosc()
            ->setRozmiar(filesize($sciezkaZapisuTymczasowego));

        return $encjaPliku;
    }
}