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

interface DaneInterface
{
    /**
     * DaneInterface constructor.
     * @param Uzytkownik $uzytkownik
     * @param $daneWejsciowe
     * @param $nazwaMetodyApi
     * @param KontenerParametrow $kontenerParametrow
     */
    public function __construct(Uzytkownik $uzytkownik, \stdClass $daneWejsciowe, $nazwaMetodyApi, KontenerParametrow $kontenerParametrow);

    /**
     * @return mixed
     */
    public function pobierz();

    /**
     * @return array
     */
    public function pobierzKolekcjeEncji(): array;
}