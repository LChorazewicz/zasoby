<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 08.07.18
 * Time: 09:57
 */

namespace ApiBundle\Library\Helper\DaneWejsciowe;

use ApiBundle\Library\Plik\Base64;

class EncjaPlikuNaPoziomieDanychWejsciowych
{
    /**
     * EncjaPlikuNaPoziomieDanychWejsciowych constructor.
     * @param string $pierwotna_nazwa
     * @param string $base64Pliku
     * @param string $katalogDoZapisuTymczasowego
     * @param string $katalogDoZapisu
     * @param DaneUzytkownikaNaPoziomieDanychWejsciowych $uzytkownikDodajacy
     */
    public function __construct(string $pierwotna_nazwa, string $base64Pliku, string $katalogDoZapisuTymczasowego, string $katalogDoZapisu, DaneUzytkownikaNaPoziomieDanychWejsciowych $uzytkownikDodajacy)
    {
        $this->encjaPliku = (new Base64())->konwertujBase64DoEncjiPliku($base64Pliku, $pierwotna_nazwa, $katalogDoZapisuTymczasowego, $katalogDoZapisu, $uzytkownikDodajacy);
    }


    public function getEncjaPliku()
    {
        return $this->encjaPliku;
    }

}