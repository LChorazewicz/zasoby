<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 07.07.18
 * Time: 18:22
 */

namespace ApiBundle\Library;


use Ramsey\Uuid\Uuid;

class Generuj
{
    public static function UnikalnaNazwe()//todo: argument
    {
        return (Uuid::uuid5(Uuid::NAMESPACE_DNS, md5(uniqid(rand(), true))))->toString();
    }

}