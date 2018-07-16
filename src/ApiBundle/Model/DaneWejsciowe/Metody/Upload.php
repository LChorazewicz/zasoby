<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.07.18
 * Time: 17:51
 */

namespace ApiBundle\Model\DaneWejsciowe\Metody;

use ApiBundle\Model\DaneWejsciowe\DaneWejscioweAbstract;
use ApiBundle\Model\DaneWejsciowe\DaneWejscioweInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Request;

class Upload extends DaneWejscioweAbstract implements DaneWejscioweInterface
{
    /**
     * Upload constructor.
     * @param Request $request
     * @param Registry $doctrine
     */
    public function __construct(Request $request, Registry $doctrine)
    {
        parent::__construct($request, $doctrine);
    }

    /**
     * @return \ApiBundle\Entity\Uzytkownik
     */
    public function getDaneUzytkownika()
    {
        return $this->daneUzytkownika();
    }

    /**
     * @return \stdClass
     */
    public function getDaneWejsciowe()
    {
        return $this->daneWejsciowe();
    }

    /**
     * @return string
     */
    public static function getNazwaMetodyApi()
    {
        return Upload::class;
    }
}