<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plik
 *
 * @ORM\Table(name="plik", indexes={@ORM\Index(name="nazwa_zasobu", columns={"nazwa_zasobu"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\PlikRepository")
 */
class Plik
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
     * @var string
     *
     * @ORM\Column(name="sciezka", type="string", length=255, nullable=false)
     */
    private $sciezka;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=16, nullable=false)
     */
    private $mimeType;

    /**
     * @return string
     */
    /**
     * @var string
     *
     * @ORM\Column(name="pierwotna_nazwa", type="string", length=255, nullable=false)
     */
    private $pierwotnaNazwa;

    /**
     * @var integer
     *
     * @ORM\Column(name="rozmiar", type="integer", nullable=false)
     */
    private $rozmiar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_dodania", type="datetime", nullable=false)
     */
    private $dataDodania;

    /**
     * @var integer
     *
     * @ORM\Column(name="uzytkownik_dodajacy", type="integer", nullable=false)
     */
    private $uzytkownikDodajacy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="czy_usuniety", type="boolean", nullable=false)
     */
    private $czyUsuniety;

    /**
     * @var string
     *
     * @ORM\Column(name="nazwa_zasobu", type="string", length=255, nullable=false)
     */
    private $nazwaZasobu;
    /**
     * @var string
     *
     * @ORM\Column(name="id_zasobu", type="string", nullable=false, length=255)
     */
    private $idZasobu;

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
    public function getSciezka(): string
    {
        return $this->sciezka;
    }

    /**
     * @param string $sciezka
     */
    public function setSciezka(string $sciezka)
    {
        $this->sciezka = $sciezka;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     */
    public function setMimeType(string $mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getPierwotnaNazwa(): string
    {
        return $this->pierwotnaNazwa;
    }

    /**
     * @param string $pierwotnaNazwa
     */
    public function setPierwotnaNazwa(string $pierwotnaNazwa)
    {
        $this->pierwotnaNazwa = $pierwotnaNazwa;
    }

    /**
     * @return int
     */
    public function getRozmiar(): int
    {
        return $this->rozmiar;
    }

    /**
     * @param int $rozmiar
     */
    public function setRozmiar(int $rozmiar)
    {
        $this->rozmiar = $rozmiar;
    }

    /**
     * @return \DateTime
     */
    public function getDataDodania(): \DateTime
    {
        return $this->dataDodania;
    }

    /**
     * @param \DateTime $dataDodania
     */
    public function setDataDodania(\DateTime $dataDodania)
    {
        $this->dataDodania = $dataDodania;
    }

    /**
     * @return int
     */
    public function getUzytkownikDodajacy(): int
    {
        return $this->uzytkownikDodajacy;
    }

    /**
     * @param int $uzytkownikDodajacy
     */
    public function setUzytkownikDodajacy(int $uzytkownikDodajacy)
    {
        $this->uzytkownikDodajacy = $uzytkownikDodajacy;
    }

    /**
     * @return bool
     */
    public function isCzyUsuniety(): bool
    {
        return $this->czyUsuniety;
    }

    /**
     * @param bool $czyUsuniety
     */
    public function setCzyUsuniety(bool $czyUsuniety)
    {
        $this->czyUsuniety = $czyUsuniety;
    }

    /**
     * @return string
     */
    public function getNazwaZasobu(): string
    {
        return $this->nazwaZasobu;
    }

    /**
     * @param string $nazwaZasobu
     */
    public function setNazwaZasobu(string $nazwaZasobu)
    {
        $this->nazwaZasobu = $nazwaZasobu;
    }

    /**
     * @return string
     */
    public function getIdZasobu(): string
    {
        return $this->idZasobu;
    }

    /**
     * @param string $idZasobu
     */
    public function setIdZasobu(string $idZasobu)
    {
        $this->idZasobu = $idZasobu;
    }

}

