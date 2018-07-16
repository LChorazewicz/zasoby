<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 15.07.18
 * Time: 17:00
 */

namespace ApiBundle\Library\Helper\DaneWejsciowe;


use ApiBundle\Entity\Uzytkownik;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Request;

interface DaneWejscioweAbstractInterface{
    /**
     * DaneWejscioweAbstractInterface constructor.
     * @param Request $request
     * @param Registry $doctrine
     */
    public function __construct(Request $request, Registry $doctrine);

    /**
     * @return Uzytkownik
     */
    public function daneUzytkownika();

    /**
     * @return \stdClass
     */
    public function daneWejsciowe();
}