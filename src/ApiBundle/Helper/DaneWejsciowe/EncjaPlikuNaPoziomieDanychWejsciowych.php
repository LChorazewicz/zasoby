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
     * @param string $katalogTymczasowy
     * @param string $katalogDocelowy
     * @param Uzytkownik $uzytkownikDodajacy
     */
    public function __construct(string $pierwotna_nazwa, string $base64Pliku, string $katalogTymczasowy, string $katalogDocelowy, Uzytkownik $uzytkownikDodajacy)
    {
        $this->encjaPliku = (new Base64())->konwertujBase64DoEncjiPliku($base64Pliku, $pierwotna_nazwa, $katalogTymczasowy, $katalogDocelowy, $uzytkownikDodajacy);
    }


    public function getEncjaPliku()
    {
        return $this->encjaPliku;
    }

}