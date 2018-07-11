<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 07.07.18
 * Time: 17:57
 */

namespace ApiBundle\Library\Helper;

use DateTime;

class EncjaPliku
{
    /**
     * @var string
     */
    private $pierwotnaNazwaPliku;
    /**
     * @var string
     */
    private $nowaNazwaPlikuBezRozszerzenia;
    /**
     * @var string
     */
    private $nowaNazwaPlikuZRozszerzeniem;
    /**
     * @var string
     */
    private $sciezkaDoPlikuNaDysku;

    /**
     * @var string
     */
    private $sciezkaDoZapisuPlikuTymczasowego;

    /**
     * @var string
     */
    private $rozszerzenie;
    /**
     * @var string
     */
    private $mimeType;
    /**
     * @var string
     */
    private $zawartosc;
    /**
     * @var int
     */
    private $rozmiar;
    /**
     * @var string
     */
    private $katalogZapisu;
    /**
     * @var string
     */
    private $katalogZapisuTymczasowego;
    /**
     * @var string
     */
    private $idZasobu;
    /**
     * @var \DateTime
     */
    private $dataDodania;

    /**
     * @var int
     */
    private $idUzytkownikaDodajacego;
    /**
     * @var string
     */
    private $loginUzytkownikaDodajacego;

    /**
     * @return string
     */
    public function getPierwotnaNazwaPliku(): string
    {
        return $this->pierwotnaNazwaPliku;
    }

    /**
     * @param string $pierwotnaNazwaPliku
     * @return $this
     */
    public function setPierwotnaNazwaPliku(string $pierwotnaNazwaPliku)
    {
        $this->pierwotnaNazwaPliku = $pierwotnaNazwaPliku;
        return $this;
    }

    /**
     * @return string
     */
    public function getNowaNazwaPlikuBezRozszerzenia(): string
    {
        return $this->nowaNazwaPlikuBezRozszerzenia;
    }

    /**
     * @param string $nowaNazwaPlikuBezRozszerzenia
     * @return $this
     */
    public function setNowaNazwaPlikuBezRozszerzenia(string $nowaNazwaPlikuBezRozszerzenia)
    {
        $this->nowaNazwaPlikuBezRozszerzenia = $nowaNazwaPlikuBezRozszerzenia;
        return $this;
    }

    /**
     * @return string
     */
    public function getNowaNazwaPlikuZRozszerzeniem(): string
    {
        return $this->nowaNazwaPlikuZRozszerzeniem;
    }

    /**
     * @param string $nowaNazwaPlikuZRozszerzeniem
     * @return $this
     */
    public function setNowaNazwaPlikuZRozszerzeniem(string $nowaNazwaPlikuZRozszerzeniem)
    {
        $this->nowaNazwaPlikuZRozszerzeniem = $nowaNazwaPlikuZRozszerzeniem;
        return $this;
    }

    /**
     * @return string
     */
    public function getSciezkaDoPlikuNaDysku(): string
    {
        return $this->sciezkaDoPlikuNaDysku;
    }

    /**
     * @param string $sciezkaDoPlikuNaDysku
     * @return $this
     */
    public function setSciezkaDoPlikuNaDysku(string $sciezkaDoPlikuNaDysku)
    {
        $this->sciezkaDoPlikuNaDysku = $sciezkaDoPlikuNaDysku;
        return $this;
    }

    /**
     * @return string
     */
    public function getSciezkaDoZapisuPlikuTymczasowego(): string
    {
        return $this->sciezkaDoZapisuPlikuTymczasowego;
    }

    /**
     * @param string $sciezkaDoZapisuPlikuTymczasowego
     * @return $this
     */
    public function setSciezkaDoZapisuPlikuTymczasowego(string $sciezkaDoZapisuPlikuTymczasowego)
    {
        $this->sciezkaDoZapisuPlikuTymczasowego = $sciezkaDoZapisuPlikuTymczasowego;
        return $this;
    }

    /**
     * @return string
     */
    public function getRozszerzenie(): string
    {
        return $this->rozszerzenie;
    }

    /**
     * @param string $rozszerzenie
     * @return $this
     */
    public function setRozszerzenie(string $rozszerzenie)
    {
        $this->rozszerzenie = $rozszerzenie;
        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     * @return $this
     */
    public function setMimeType(string $mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @return string
     */
    public function getZawartosc(): string
    {
        return $this->zawartosc;
    }

    /**
     * @param string $zawartosc
     * @return $this
     */
    public function setZawartosc(string $zawartosc)
    {
        $this->zawartosc = $zawartosc;
        return $this;
    }

    /**
     * @return int
     */
    public function getRozmiar(): int
    {
        return $this->rozmiar;
    }

    /**
     * @param int $rozmiar
     * @return $this
     */
    public function setRozmiar(int $rozmiar)
    {
        $this->rozmiar = $rozmiar;
        return $this;
    }

    /**
     * @return string
     */
    public function getKatalogZapisu(): string
    {
        return $this->katalogZapisu;
    }

    /**
     * @param string $katalogZapisu
     * @return $this
     */
    public function setKatalogZapisu(string $katalogZapisu)
    {
        $this->katalogZapisu = $katalogZapisu;
        return $this;
    }

    /**
     * @return string
     */
    public function getKatalogZapisuTymczasowego(): string
    {
        return $this->katalogZapisuTymczasowego;
    }

    /**
     * @param string $katalogZapisuTymczasowego
     * @return $this
     */
    public function setKatalogZapisuTymczasowego(string $katalogZapisuTymczasowego)
    {
        $this->katalogZapisuTymczasowego = $katalogZapisuTymczasowego;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdZasobu(): string
    {
        return $this->idZasobu;
    }

    /**
     * @param string $idZasobu
     * @return $this
     */
    public function setIdZasobu(string $idZasobu)
    {
        $this->idZasobu = $idZasobu;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDataDodania(): \DateTime
    {
        return $this->dataDodania;
    }

    /**
     * @param \DateTime $dataDodania
     * @return $this
     */
    public function setDataDodania(\DateTime $dataDodania)
    {
        $this->dataDodania = $dataDodania;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUzytkownikaDodajacego(): int
    {
        return $this->idUzytkownikaDodajacego;
    }

    /**
     * @param int $idUzytkownikaDodajacego
     * @return $this
     */
    public function setIdUzytkownikaDodajacego(int $idUzytkownikaDodajacego)
    {
        $this->idUzytkownikaDodajacego = $idUzytkownikaDodajacego;
        return $this;
    }

    /**
     * @return string
     */
    public function getLoginUzytkownikaDodajacego(): string
    {
        return $this->loginUzytkownikaDodajacego;
    }

    /**
     * @param string $loginUzytkownikaDodajacego
     * @return $this
     */
    public function setLoginUzytkownikaDodajacego(string $loginUzytkownikaDodajacego)
    {
        $this->loginUzytkownikaDodajacego = $loginUzytkownikaDodajacego;
        return $this;
    }

}