<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 15.07.18
 * Time: 16:27
 */

namespace ApiBundle\Library\Helper\DaneWejsciowe;


use ApiBundle\Services\KontenerParametrow;

class DaneWejsciowePatch implements DaneWejscioweInterface
{
    private $login;
    private $haslo;
    private $daneUzytkownika;
    private $token;

    private $szkic;
    private $pierwotnaNazwa;
    private $mimeType;
    private $rozmiar;
    private $strumien;
    private $koniec;

    private $params;

    private $idZasobu;
    private $danePliku;
    private $daneWejsciowe;


    /**
     * DaneWejsciowePatch constructor.
     * @param $daneWejsciowe
     * @param KontenerParametrow $kontenerParametrow
     */
    public function __construct($daneWejsciowe, KontenerParametrow $kontenerParametrow)
    {
        $this->token = $daneWejsciowe->token;
        $this->params = [
            'katalog_do_zapisu_plikow_tymczasowych' => $kontenerParametrow->pobierzParametrZConfigu('katalog_do_zapisu_plikow_tymczasowych'),
            'katalog_do_zapisu_plikow' => $kontenerParametrow->pobierzParametrZConfigu('katalog_do_zapisu_plikow')
        ];
        $this->setDaneUzytkownika($daneWejsciowe->login, $daneWejsciowe->haslo);
        $this->setDaneWejsciowe($daneWejsciowe->dane_wejsciowe);

        if(isset($daneWejsciowe->dane_pliku)){
            $this->setDanePliku($daneWejsciowe->dane_pliku);
        }
    }

    private function setDaneUzytkownika($login, $haslo)
    {
        $this->login = $login;
        $this->haslo = $haslo;
        $this->daneUzytkownika = new DaneUzytkownikaNaPoziomieDanychWejsciowych($login, $haslo);
        return $this;
    }

    private function setDaneWejsciowe($parametry_wejsciowe)
    {
        $this->idZasobu = $parametry_wejsciowe->id_zasobu;
        $this->szkic = $parametry_wejsciowe->szkic;
        $this->strumien = $parametry_wejsciowe->strumien;
        $this->koniec = $parametry_wejsciowe->koniec;
        $this->daneWejsciowe = new DaneWejscioweNaPoziomieMetodyPatch();
    }

    /**
     * @return DaneUzytkownikaNaPoziomieDanychWejsciowych
     */
    public function getDaneUzytkownika()
    {
        return $this->daneUzytkownika;
    }

    private function setDanePliku($dane_wejsciowe)
    {
        $this->pierwotnaNazwa = $dane_wejsciowe->pierwotna_nazwa;
        $this->mimeType = $dane_wejsciowe->mime_type;
        $this->rozmiar = $dane_wejsciowe->rozmiar;
        $this->danePliku = new DanePlikuNaPoziomieMetodyPatch();
    }

    public function getDanePliku()
    {
        return $this->danePliku;
    }

    public function getDaneWejsciowe()
    {
        return $this->daneWejsciowe;
    }
}