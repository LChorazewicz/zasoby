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
     * @var string
     */
    private $pierwotnaNazwa;
    /**
     * @var string
     */
    private $base64Pliku;
    /**
     * @var \ApiBundle\Library\Helper\EncjaPliku
     */
    private $encjaPliku;
    /**
     * @var string
     */
    private $katalogDoZapisu;

    /**
     * @var DaneUzytkownikaNaPoziomieDanychWejsciowych
     */
    private $uzytkownikDodajacy;

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
        $this->pierwotnaNazwa = $pierwotna_nazwa;
        $this->base64Pliku = $base64Pliku;
        $this->katalogDoZapisuTymczasowego = $katalogDoZapisuTymczasowego;
        $this->katalogDoZapisu = $katalogDoZapisu;
        $this->uzytkownikDajacy = $uzytkownikDodajacy;
        $this->encjaPliku = (new Base64())->konwertujBase64DoEncjiPliku($base64Pliku, $pierwotna_nazwa, $katalogDoZapisuTymczasowego, $katalogDoZapisu, $uzytkownikDodajacy);
    }


    public function getEncjaPliku()
    {
        return $this->encjaPliku;
    }

}