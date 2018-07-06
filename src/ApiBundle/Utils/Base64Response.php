<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 07.07.18
 * Time: 01:04
 */

namespace ApiBundle\Utils;



use Symfony\Component\HttpFoundation\Response;

class Base64Response
{
    public function __construct($base64, $contentType)
    {
        $plik = str_replace('data:', '', $base64);
        $plik = str_replace('base64,', '', $base64);

        $zawartosc = explode(';', $plik)[1];
        return new Response(base64_decode($zawartosc), Response::HTTP_OK);
    }
}