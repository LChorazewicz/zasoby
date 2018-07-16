<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.07.18
 * Time: 20:12
 */

namespace ApiBundle\Library\WarunkiBrzegowe;


use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Model\DaneWejsciowe\DaneWejscioweInterface;

class Uprawnienia
{
    public function sprawdzUprawnieniaDoMetody(DaneWejscioweInterface $daneWejsciowe)
    {
        $wynik = [];

        if(!self::uzytkownikPosiadaUprawnieniaDoMetody($daneWejsciowe->getDaneUzytkownika(), $daneWejsciowe::getNazwaMetodyApi())){
            $wynik[] = false;
        }

        if(self::uzytkownikDodajacyPrzekroczylLimituObslugiMetody($daneWejsciowe->getDaneUzytkownika(), $daneWejsciowe::getNazwaMetodyApi())){
            $wynik[] = false;
        }

        return !(in_array(false, $wynik, true));
    }

    public static function uzytkownikPosiadaUprawnieniaDoMetody(Uzytkownik $uzytkownik, $nazwaMetody)
    {
        return true;
    }

    public static function uzytkownikDodajacyPrzekroczylLimituObslugiMetody(Uzytkownik $uzytkownik, $nazwaMetody)
    {
        return false;
    }
}