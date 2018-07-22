<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 22.07.18
 * Time: 12:04
 */

namespace ApiBundle\Helper\DaneWejsciowe;


class Nazwa
{
    /**
     * @var string
     */
    private $pierwotnaNazwaBezRozszerzenia;

    /**
     * @var string
     */
    private $pierwotnaNazwaZRozszerzenem;

    /**
     * @var string
     */
    private $rozszerzenie;

    public function __construct(string $pierwotnaNazwaZRozszeniem)
    {
        $this->pierwotnaNazwaZRozszerzenem = $pierwotnaNazwaZRozszeniem;
        $pierwotnaNazwaIRozszerznie = explode('.', $pierwotnaNazwaZRozszeniem);
        $this->pierwotnaNazwaBezRozszerzenia = $pierwotnaNazwaIRozszerznie[0];
        $this->rozszerzenie = $pierwotnaNazwaIRozszerznie[1];
    }

    /**
     * @return string
     */
    public function getPierwotnaNazwaBezRozszerzenia(): string
    {
        return $this->pierwotnaNazwaBezRozszerzenia;
    }

    /**
     * @param string $pierwotnaNazwaBezRozszerzenia
     */
    public function setPierwotnaNazwaBezRozszerzenia(string $pierwotnaNazwaBezRozszerzenia)
    {
        $this->pierwotnaNazwaBezRozszerzenia = $pierwotnaNazwaBezRozszerzenia;
    }

    /**
     * @return string
     */
    public function getPierwotnaNazwaZRozszerzenem(): string
    {
        return $this->pierwotnaNazwaZRozszerzenem;
    }

    /**
     * @param string $pierwotnaNazwaZRozszerzenem
     */
    public function setPierwotnaNazwaZRozszerzenem(string $pierwotnaNazwaZRozszerzenem)
    {
        $this->pierwotnaNazwaZRozszerzenem = $pierwotnaNazwaZRozszerzenem;
    }

    /**
     * @return string
     */
    public function getRozszerzenie(): string
    {
        return $this->rozszerzenie;
    }

    /**
     * @param string $rozszerzenie
     */
    public function setRozszerzenie(string $rozszerzenie)
    {
        $this->rozszerzenie = $rozszerzenie;
    }
}