<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plik
 *
 * @ORM\Table(name="plik")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\PlikRepository")
 */
class Plik
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sciezka", type="string", length=255, unique=true)
     */
    private $sciezka;

    /**
     * @var string
     *
     * @ORM\Column(name="pierwotna_nazwa", type="string", length=255)
     */
    private $pierwotnaNazwa;

    /**
     * @var int
     *
     * @ORM\Column(name="rozmiar", type="integer")
     */
    private $rozmiar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_dodania", type="datetime")
     */
    private $dataDodania;

    /**
     * @var int
     *
     * @ORM\Column(name="uzytkownik_dodajacy", type="integer")
     */
    private $uzytkownikDodajacy;

    /**
     * @var bool
     *
     * @ORM\Column(name="czy_usuniety", type="boolean")
     */
    private $czyUsuniety;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sciezka
     *
     * @param string $sciezka
     *
     * @return Plik
     */
    public function setSciezka($sciezka)
    {
        $this->sciezka = $sciezka;

        return $this;
    }

    /**
     * Get sciezka
     *
     * @return string
     */
    public function getSciezka()
    {
        return $this->sciezka;
    }

    /**
     * Set pierwotnaNazwa
     *
     * @param string $pierwotnaNazwa
     *
     * @return Plik
     */
    public function setPierwotnaNazwa($pierwotnaNazwa)
    {
        $this->pierwotnaNazwa = $pierwotnaNazwa;

        return $this;
    }

    /**
     * Get pierwotnaNazwa
     *
     * @return string
     */
    public function getPierwotnaNazwa()
    {
        return $this->pierwotnaNazwa;
    }

    /**
     * Set rozmiar
     *
     * @param integer $rozmiar
     *
     * @return Plik
     */
    public function setRozmiar($rozmiar)
    {
        $this->rozmiar = $rozmiar;

        return $this;
    }

    /**
     * Get rozmiar
     *
     * @return int
     */
    public function getRozmiar()
    {
        return $this->rozmiar;
    }

    /**
     * Set dataDodania
     *
     * @param \DateTime $dataDodania
     *
     * @return Plik
     */
    public function setDataDodania($dataDodania)
    {
        $this->dataDodania = $dataDodania;

        return $this;
    }

    /**
     * Get dataDodania
     *
     * @return \DateTime
     */
    public function getDataDodania()
    {
        return $this->dataDodania;
    }

    /**
     * Set uzytkownikDodajacy
     *
     * @param integer $uzytkownikDodajacy
     *
     * @return Plik
     */
    public function setUzytkownikDodajacy($uzytkownikDodajacy)
    {
        $this->uzytkownikDodajacy = $uzytkownikDodajacy;

        return $this;
    }

    /**
     * Get uzytkownikDodajacy
     *
     * @return int
     */
    public function getUzytkownikDodajacy()
    {
        return $this->uzytkownikDodajacy;
    }

    /**
     * Set czyUsuniety
     *
     * @param boolean $czyUsuniety
     *
     * @return Plik
     */
    public function setCzyUsuniety($czyUsuniety)
    {
        $this->czyUsuniety = $czyUsuniety;

        return $this;
    }

    /**
     * Get czyUsuniety
     *
     * @return bool
     */
    public function getCzyUsuniety()
    {
        return $this->czyUsuniety;
    }
}

