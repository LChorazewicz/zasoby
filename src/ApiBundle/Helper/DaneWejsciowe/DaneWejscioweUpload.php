<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 08.07.18
 * Time: 09:34
 */

namespace ApiBundle\Library\Helper\DaneWejsciowe;


use ApiBundle\Exception\PustaKolekcjaException;
use ApiBundle\Services\KontenerParametrow;
use ApiBundle\Utils\Data;

class DaneUpload
{
    private $token;
    private $login;
    private $haslo;
    private $kolekcjaPlikow;
    private $daneUzytkownika;
    private $params;

    public function __construct($daneWejsciowe, KontenerParametrow $kontenerParametrow)
    {
        $this->token = $daneWejsciowe->token;
        $this->params = [
            'katalog_do_zapisu_plikow_tymczasowych' => $kontenerParametrow->pobierzParametrZConfigu('katalog_do_zapisu_plikow_tymczasowych'),
            'katalog_do_zapisu_plikow' => $kontenerParametrow->pobierzParametrZConfigu('katalog_do_zapisu_plikow')
        ];
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
     * @param $uzytkownikaDodajacy Uzytkownik
     * @return $this
     * @throws PustaKolekcjaException
     */
    public function setKolekcjaPlikow($kolekcjaPlikow, $uzytkownikaDodajacy)
    {
        $kolekcja = [];
        foreach ($kolekcjaPlikow as $plik){
            $obiekt =  new EncjaPlikuNaPoziomieDanychWejsciowych(
                $plik->pierwotna_nazwa,
                $plik->base64,
                $this->params['katalog_do_zapisu_plikow_tymczasowych'],
                $this->params['katalog_do_zapisu_plikow'] . Data::pobierzDzisiejszaDateWFormacieKrotkim() . '/',
                $uzytkownikaDodajacy);
            if(is_null($obiekt->getEncjaPliku())){
                continue;
            }
            $kolekcja[] = $obiekt;
        }

        if(empty($kolekcja)){
            throw new PustaKolekcjaException();
        }

        $this->kolekcjaPlikow = $kolekcja;
        return $this;
    }
}