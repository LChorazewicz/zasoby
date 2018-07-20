<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 12.07.18
 * Time: 01:08
 */

namespace ApiBundle\Services;


use ApiBundle\Library\Plik;

class ZapisPlikowNaDyskuService
{
    public function zapisz($lokalizacja, $zawartosc)
    {
        return (new Plik())->zapisz($lokalizacja, $zawartosc);
    }
}