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

abstract class DaneAbstract implements DaneAbstractInterface
{

    /**
     * @var Uzytkownik
     */
    private $uzytkownik;

    /**
     * @var \stdClass
     */
    private $daneWejsciowe;

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
     * @param \stdClass $daneWejsciowe
     * @param $nazwaMetodyApi
     * @param KontenerParametrow $kontenerParametrow
     */
    public function __construct(Uzytkownik $uzytkownik, \stdClass $daneWejsciowe, $nazwaMetodyApi, KontenerParametrow $kontenerParametrow)
    {
        $this->uzytkownik = $uzytkownik;
        $this->daneWejsciowe = $daneWejsciowe;
        $this->nazwaMetodyApi = $nazwaMetodyApi;
        $this->kontenerParametrow = $kontenerParametrow;
    }

    public function zmienna($nazwaParametru)
    {
        return $this->kontenerParametrow->pobierz($nazwaParametru);
    }

    public function daneUzytkownika()
    {
        return $this->uzytkownik;
    }

    public function daneWejsciowe()
    {
        return $this->daneWejsciowe;
    }

    public function nazwaMetodyApi()
    {
        return $this->nazwaMetodyApi;
    }

    public function kontenerParametrow()
    {
        return $this->kontenerParametrow;
    }
}