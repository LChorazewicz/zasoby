<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.07.18
 * Time: 20:45
 */

namespace ApiBundle\Model\Dane;


use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Services\KontenerParametrow;

interface DaneAbstractInterface
{
    /**
     * DaneAbstractInterface constructor.
     * @param Uzytkownik $uzytkownik
     * @param $idZasobu
     * @param $nazwaMetodyApi
     * @param KontenerParametrow $kontenerParametrow
     */
    public function __construct(Uzytkownik $uzytkownik, $idZasobu, $nazwaMetodyApi, KontenerParametrow $kontenerParametrow);

    /**
     * @return Uzytkownik
     */
    public function daneUzytkownika();

    /**
     * @return string
     */
    public function idZasobu();

    /**
     * @return string
     */
    public function nazwaMetodyApi();
}