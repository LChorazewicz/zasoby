<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 12.07.18
 * Time: 19:35
 */

namespace ApiBundle\Library\WarunkiBrzegowe;



use ApiBundle\Helper\EncjaPliku;

class Plik
{
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

    public static function sprawdzRozmiar($rozmiar)
    {
        return $rozmiar < 20000000;
    }
}