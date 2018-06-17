<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Uzytkownik
 *
 * @ORM\Table(name="uzytkownik", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_9EF01856AA08CB10", columns={"login"}), @ORM\UniqueConstraint(name="UNIQ_9EF01856E7927C74", columns={"email"}), @ORM\UniqueConstraint(name="UNIQ_9EF01856897DA477", columns={"telefon"}), @ORM\UniqueConstraint(name="UNIQ_9EF018563931747B", columns={"pesel"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\UzytkownikRepository")
 */
class Uzytkownik
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
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
     * @return int
     */
    public function getIdgrupy(): int
    {
        return $this->idgrupy;
    }

    /**
     * @param int $idgrupy
     */
    public function setIdgrupy(int $idgrupy)
    {
        $this->idgrupy = $idgrupy;
    }

    /**
     * @return string
     */
    public function getImie(): string
    {
        return $this->imie;
    }

    /**
     * @param string $imie
     */
    public function setImie(string $imie)
    {
        $this->imie = $imie;
    }

    /**
     * @return string
     */
    public function getNazwisko(): string
    {
        return $this->nazwisko;
    }

    /**
     * @param string $nazwisko
     */
    public function setNazwisko(string $nazwisko)
    {
        $this->nazwisko = $nazwisko;
    }

    /**
     * @return string
     */
    public function getTelefon(): string
    {
        return $this->telefon;
    }

    /**
     * @param string $telefon
     */
    public function setTelefon(string $telefon)
    {
        $this->telefon = $telefon;
    }

    /**
     * @return string
     */
    public function getPesel(): string
    {
        return $this->pesel;
    }

    /**
     * @param string $pesel
     */
    public function setPesel(string $pesel)
    {
        $this->pesel = $pesel;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=32, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="haslo", type="string", length=255, nullable=false)
     */
    private $haslo;

    /**
     * @var integer
     *
     * @ORM\Column(name="idGrupy", type="smallint", nullable=false)
     */
    private $idgrupy;

    /**
     * @var string
     *
     * @ORM\Column(name="imie", type="string", length=32, nullable=false)
     */
    private $imie;

    /**
     * @var string
     *
     * @ORM\Column(name="nazwisko", type="string", length=32, nullable=false)
     */
    private $nazwisko;

    /**
     * @var string
     *
     * @ORM\Column(name="telefon", type="string", length=9, nullable=false)
     */
    private $telefon;

    /**
     * @var string
     *
     * @ORM\Column(name="pesel", type="string", length=11, nullable=false)
     */
    private $pesel;


}

