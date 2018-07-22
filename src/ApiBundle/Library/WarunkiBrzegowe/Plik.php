<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 12.07.18
 * Time: 19:35
 */

namespace ApiBundle\Library\WarunkiBrzegowe;

use ApiBundle\Helper\EncjaPliku;
use ApiBundle\Repository\PlikRepository;
use ApiBundle\Repository\UzytkownikRepository;
use ApiBundle\Services\KontenerParametrow;

class Plik
{
    /**
     * @var KontenerParametrow
     */
    private $parametr;

    /**
     * @var integer
     */
    private static $maksymalnyRozmiarPlikuWMegaBajtach;

    /**
     * Plik constructor.
     * @param KontenerParametrow $kontenerParametrow
     */
    public function __construct(KontenerParametrow $kontenerParametrow)
    {
        $this->parametr = $kontenerParametrow;
        self::$maksymalnyRozmiarPlikuWMegaBajtach = $this->parametr->pobierz('maksymalny_rozmiar_pliku_w_megabajtach');
    }

    /**
     * @param EncjaPliku $encjaPliku
     * @return bool
     */
    public static function czyEncjaPlikuKwalifikujeSieDoZapisu(EncjaPliku $encjaPliku)
    {
        return self::sprawdzMimeType($encjaPliku->getMimeType()) &&
            self::sprawdzRozmiar($encjaPliku->getRozmiar());

    }

    /**
     * @param $mimeType
     * @return bool
     */
    private static function sprawdzMimeType($mimeType)
    {
        $czySieKwalifikuje = true;

        switch ($mimeType){
            case 'application/pdf':
            case 'application/octet-stream':
            /*obrazy*/
            case 'image/gif':
            case 'image/jpeg':
            case 'image/png':
            case 'image/tiff':
            /*audio*/
            case 'audio/mpeg':
            case 'audio/x-ms-wma':
            case 'audio/vnd.rn-realaudio':
            case 'audio/x-wav':
            case 'audio/ogg':
            /*text*/
            case 'text/css':
            case 'text/html':
            case 'application/javascript':
            case 'text/plain':
            case 'text/xml':
            /*video*/
            case 'video/mpeg':
            case 'video/mp4':
            case 'video/quicktime':
            case 'video/x-ms-wmv':
            case 'video/ogg': break;
            default:{
                $czySieKwalifikuje = false;
            }
        }
        return $czySieKwalifikuje;
    }

    /**
     * @param $rozmiar
     * @return bool
     */
    private static function sprawdzRozmiar($rozmiar)
    {
        return !(\ApiBundle\Library\Plik::zamienBajtyNaMegaBajty($rozmiar)  > self::$maksymalnyRozmiarPlikuWMegaBajtach);
    }

    /**
     * @param $kolekcja
     * @param $maksymalnyRozmiar
     * @return bool
     */
    public static function rozmiarKolekcjiPlikowJestWiekszyNiz($kolekcja, $maksymalnyRozmiar)
    {
        $rozmiar = 0;

        /**
         * @var $plik EncjaPliku
         */
        foreach ($kolekcja as $plik){
            $rozmiar = $rozmiar + $plik->getRozmiar();
        }

        return \ApiBundle\Library\Plik::zamienBajtyNaMegaBajty($rozmiar) > $maksymalnyRozmiar;
    }

    private static function uzytkownikProbujeNadpisacPlikKtoryNieJestSzkicem(){return false;}

    public static function szkicPlikuKwalifikujeSieDoZapisu(\ApiBundle\Entity\Plik $plik, PlikRepository $plikRepository, UzytkownikRepository $uzytkownik)
    {
        if(!self::uzytkownikProbujeNadpisacPlikKtoryNieJestSzkicem()){
            return true;
        }
        return false;
    }

    public static function rozszerzeniePlikiPasujeDoMimeType(string $mimeType)
    {
        //
    }

    public function uzytkownikProbujeWykonacAtakTypuDDoS()
    {

    }
}