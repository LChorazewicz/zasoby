<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 08.07.18
 * Time: 09:34
 */

namespace ApiBundle\Library\Helper\DaneWejsciowe;


use ApiBundle\Utils\Data;

class DaneWejscioweUpload
{
    private $token;
    private $login;
    private $haslo;
    private $kolekcjaPlikow;
    private $daneUzytkownika;
    private $params;

    public function __construct($daneWejsciowe, array $params)
    {
        $this->token = $daneWejsciowe->token;
        $this->params = $params;
        $this->setDaneUzytkownika($daneWejsciowe->login, $daneWejsciowe->haslo);
        $this->setKolekcjaPlikow($daneWejsciowe->pliki, $this->getDaneUzytkownika());
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @param mixed $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @param mixed $haslo
     * @return $this
     */
    public function setHaslo($haslo)
    {
        $this->haslo = $haslo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKolekcjaPlikow()
    {
        return $this->kolekcjaPlikow;
    }

    /**
     * @param array $kolekcjaPlikow
     * @param $uzytkownikaDodajacy DaneUzytkownikaNaPoziomieDanychWejsciowych
     * @return $this
     */
    public function setKolekcjaPlikow($kolekcjaPlikow, $uzytkownikaDodajacy)
    {
        $kolekcja = [];
        foreach ($kolekcjaPlikow as $plik){
            $kolekcja[] = new EncjaPlikuNaPoziomieDanychWejsciowych(
                $plik->pierwotna_nazwa,
                $plik->base64,
                $this->params['katalog_do_zapisu_plikow_tymczasowych'],
                $this->params['katalog_do_zapisu_plikow'] . Data::pobierzDzisiejszaDateWFormacieKrotkim() . '/',
                $uzytkownikaDodajacy);
        }
        $this->kolekcjaPlikow = $kolekcja;
        return $this;
    }

    private function setDaneUzytkownika($login, $haslo)
    {
        $this->login = $login;
        $this->haslo = $haslo;
        $this->daneUzytkownika = new DaneUzytkownikaNaPoziomieDanychWejsciowych($login, $haslo);
        return $this;
    }

    /**
     * @return DaneUzytkownikaNaPoziomieDanychWejsciowych
     */
    public function getDaneUzytkownika()
    {
        return $this->daneUzytkownika;
    }
}