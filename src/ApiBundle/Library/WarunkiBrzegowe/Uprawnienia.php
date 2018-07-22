<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.07.18
 * Time: 20:12
 */

namespace ApiBundle\Library\WarunkiBrzegowe;


use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Exception\UzytkownikNiePosiadaUprawnienException;
use ApiBundle\Model\DaneWejsciowe\DaneWejscioweInterface;

class Uprawnienia
{
    /**
     * @param DaneWejscioweInterface $daneWejsciowe
     * @return bool
     * @throws UzytkownikNiePosiadaUprawnienException
     */
    public function sprawdzUprawnieniaDoMetody(DaneWejscioweInterface $daneWejsciowe)
    {
        $wynik = [];

        if(!self::uzytkownikPosiadaUprawnieniaDoMetody($daneWejsciowe->getDaneUzytkownika(), $daneWejsciowe::getNazwaMetodyApi())){
            $wynik[] = false;
        }

        if(self::uzytkownikDodajacyPrzekroczylLimituObslugiMetody($daneWejsciowe->getDaneUzytkownika(), $daneWejsciowe::getNazwaMetodyApi())){
            $wynik[] = false;
        }

        if(in_array(false, $wynik, true)){
            throw new UzytkownikNiePosiadaUprawnienException();
        }

        return true;
    }

    public static function uzytkownikPosiadaUprawnieniaDoMetody(Uzytkownik $uzytkownik, $nazwaMetody)
    {
        switch ($nazwaMetody){
            case 'Upload': return true;
            case 'StrumienSzkic': return true;
        }
        return false;
    }

    public static function uzytkownikDodajacyPrzekroczylLimituObslugiMetody(Uzytkownik $uzytkownik, $nazwaMetody)
    {
        return false;
    }
}