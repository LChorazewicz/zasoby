<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 20.07.18
 * Time: 21:08
 */

namespace ApiBundle\RabbitMQ\Producer\Helper;


use ApiBundle\RabbitMQ\Kolejka;

class WysylkaEmailowHelper
{
    /**
     * @var string
     */
    private $nadawca;

    /**
     * @var string
     */
    private $odbiorca;

    /**
     * @var string
     */
    private $temat;

    /**
     * @var string
     */
    private $wiadomosc;

    /**
     * @var Kolejka
     */
    private $kolejka;

    /**
     * @return string
     */
    public function getNadawca(): string
    {
        return $this->nadawca;
    }

    /**
     * @param string $nadawca
     * @return $this
     */
    public function setNadawca(string $nadawca)
    {
        $this->nadawca = $nadawca;
        return $this;
    }

    /**
     * @return string
     */
    public function getOdbiorca(): string
    {
        return $this->odbiorca;
    }

    /**
     * @param string $odbiorca
     * @return $this
     */
    public function setOdbiorca(string $odbiorca)
    {
        $this->odbiorca = $odbiorca;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemat(): string
    {
        return $this->temat;
    }

    /**
     * @param string $temat
     * @return $this
     */
    public function setTemat(string $temat)
    {
        $this->temat = $temat;
        return $this;
    }

    /**
     * @return string
     */
    public function getWiadomosc(): string
    {
        return $this->wiadomosc;
    }

    /**
     * @param string $wiadomosc
     * @return $this
     */
    public function setWiadomosc(string $wiadomosc)
    {
        $this->wiadomosc = $wiadomosc;
        return $this;
    }

    /**
     * @return Kolejka
     */
    public function getKolejka(): Kolejka
    {
        return $this->kolejka;
    }

    /**
     * @param Kolejka $kolejka
     * @return $this
     */
    public function setKolejka(Kolejka $kolejka)
    {
        $this->kolejka = $kolejka;
        return $this;
    }

}