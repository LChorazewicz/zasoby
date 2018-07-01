<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 26.06.18
 * Time: 01:11
 */

namespace ApiBundle\Model\Guzzle;


use GuzzleHttp\Client;

class Fabryka
{
    public static function stworzKlienta()
    {
        return new Client();
    }
}