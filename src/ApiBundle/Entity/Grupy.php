<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grupy
 *
 * @ORM\Table(name="grupy")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\GrupyRepository")
 */
class Grupy
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
     * @ORM\Column(name="nazwa", type="string", length=32, unique=true)
     */
    private $nazwa;

    /**
     * @var string
     *
     * @ORM\Column(name="uprawnienia", type="string", length=255)
     */
    private $uprawnienia;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;


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
     * Set nazwa
     *
     * @param string $nazwa
     *
     * @return Grupy
     */
    public function setNazwa($nazwa)
    {
        $this->nazwa = $nazwa;

        return $this;
    }

    /**
     * Get nazwa
     *
     * @return string
     */
    public function getNazwa()
    {
        return $this->nazwa;
    }

    /**
     * Set uprawnienia
     *
     * @param string $uprawnienia
     *
     * @return Grupy
     */
    public function setUprawnienia($uprawnienia)
    {
        $this->uprawnienia = $uprawnienia;

        return $this;
    }

    /**
     * Get uprawnienia
     *
     * @return string
     */
    public function getUprawnienia()
    {
        return $this->uprawnienia;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Grupy
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }
}

