<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.07.18
 * Time: 20:41
 */

namespace ApiBundle\Model\Dane;


use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Services\KontenerParametrow;

class DaneAbstract implements DaneAbstractInterface
{

    /**
     * @var Uzytkownik
     */
    private $uzytkownik;

    /**
     * @var string
     */
    private $idZasobu;

    /**
     * @var string
     */
    private $nazwaMetodyApi;

    /**
     * @var KontenerParametrow $kontenerParametrow
     */
    private $kontenerParametrow;

    /**
     * DaneAbstract constructor.
     * @param Uzytkownik $uzytkownik
     * @param $idZasobu
     * @param $nazwaMetodyApi
     * @param KontenerParametrow $kontenerParametrow
     */
    public function __construct(Uzytkownik $uzytkownik, $idZasobu, $nazwaMetodyApi, KontenerParametrow $kontenerParametrow)
    {
        $this->uzytkownik = $uzytkownik;
        $this->idZasobu = $idZasobu;
        $this->nazwaMetodyApi = $nazwaMetodyApi;
        $this->kontenerParametrow = $kontenerParametrow;
    }

    public function zmienna($nazwaParametru)
    {
        return $this->kontenerParametrow->pobierz($nazwaParametru);
    }

    public function getDaneUzytkownika()
    {
        return $this->getDaneUzytkownika();
    }

    public function getIdZasobu()
    {
        return $this->idZasobu;
    }

    public function getNazwaMetodyApi()
    {
        return $this->nazwaMetodyApi;
    }

    public function getKontenerParametrow()
    {
        return $this->kontenerParametrow;
    }
}