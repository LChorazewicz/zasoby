<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 08.07.18
 * Time: 10:23
 */

namespace ApiBundle\Helper\DaneWejsciowe;


class Uzytkownik
{

    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $haslo;
    /**
     * @var int
     */
    private $id = 0;

    /**
     * DaneUzytkownikaNaPoziomieDanychWejsciowych constructor.
     * @param $login
     * @param $haslo
     */
    public function __construct(string $login, string $haslo)
    {
        $this->login = $login;
        $this->haslo = $haslo;
    }

    /**
     * @return string
     */
    public function getHaslo(): string
    {
        return $this->haslo;
    }

    /**
     * @param string $haslo
     */
    public function setHaslo(string $haslo)
    {
        $this->haslo = $haslo;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login)
    {
        $this->login = $login;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}