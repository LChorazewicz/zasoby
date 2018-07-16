<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 12.07.18
 * Time: 16:50
 */

namespace ApiBundle\Library\Helper\DaneWejsciowe;
use ApiBundle\Exception\WarunkiBrzegoweNieZostalySpelnioneException;

/**
 * Class Base64
 * @package ApiBundle\Library\Helper\DaneWejsciowe
 * @note: Klasa odpowiada za podstawową obróbkę pliku w formacie Base64 do postaci
 *        umożliwiającej wyciągnięcie podstawowych właściwości pliku.
 */
class Base64
{
    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $zawartosc;

    /**
     * @var string
     */
    private $rozszerzenie;

    /**
     * @var string
     */
    private $pierwotnaNazwa;

    /**
     * Base64 constructor.
     * @param $base64
     * @param $pierwotnaNazwa
     * @throws WarunkiBrzegoweNieZostalySpelnioneException
     */
    public function __construct($base64, $pierwotnaNazwa)
    {
        $base64 = str_replace('data:', '', $base64);
        $base64 = str_replace('base64,', '', $base64);
        $mimeTypeIBase64 = explode(';', $base64);
        $nazwa = explode('.', $pierwotnaNazwa);

        if(empty($nazwa) || strlen($nazwa[1]) < 2 || empty($mimeTypeIBase64) || empty($mimeTypeIBase64[0]) || empty($mimeTypeIBase64[1])) {
            throw new WarunkiBrzegoweNieZostalySpelnioneException();
        }

        $this->mimeType = $mimeTypeIBase64[0];
        $this->zawartosc = $mimeTypeIBase64[1];

        $this->pierwotnaNazwa = $nazwa[0];
        $this->rozszerzenie = $nazwa[1];
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getRozszerzenie(): string
    {
        return $this->rozszerzenie;
    }

    /**
     * @return string
     */
    public function getPierwotnaNazwa(): string
    {
        return $this->pierwotnaNazwa;
    }

    /**
     * @return string
     */
    public function getOdkodowanaZawartosc()
    {
        return base64_decode($this->zawartosc);
    }

    /**
     * @return string
     */
    public function getZakodowanaZawartosc()
    {
        return $this->zawartosc;
    }
}