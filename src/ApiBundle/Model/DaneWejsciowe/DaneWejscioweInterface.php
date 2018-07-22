<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.07.18
 * Time: 18:20
 */

namespace ApiBundle\Model\DaneWejsciowe;


use ApiBundle\Entity\Uzytkownik;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Request;

interface DaneWejscioweInterface
{
    /**
     * DaneWejscioweInterface constructor.
     * @param Request $request
     * @param Registry $doctrine
     */
    public function __construct(Request $request, Registry $doctrine);

    /**
     * @return \stdClass
     */
    public function getDaneWejsciowe();

    /**
     * @return Uzytkownik
     */
    public function getDaneUzytkownika();

    /**
     * @return string
     */
    public static function getNazwaMetodyApi();

    /**
     * @param \stdClass $daneWejsciowe
     * @return bool
     */
    public function walidujDaneWejsciowe(\stdClass $daneWejsciowe): bool;
}