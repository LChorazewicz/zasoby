<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.06.18
 * Time: 17:23
 */

namespace ApiBundle\Utils;


class Data
{
    public static function pobierzDzisiejszaDateWFormacieKrotkim()
    {
        return (new \DateTime())->format('Y-m-d');
    }

    public static function pobierzDzisiejszaDateWFormacieDlugim()
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}