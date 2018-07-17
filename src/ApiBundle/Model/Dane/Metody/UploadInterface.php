<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.07.18
 * Time: 23:09
 */

namespace ApiBundle\Model\Dane\Metody;


use ApiBundle\Entity\Uzytkownik;

interface UploadInterface
{

    /**
     * Zwraca kolekcje obiektów EncjaPliku
     */
    public function pobierzKolekcjePlikow();

    /**
     * @return Uzytkownik
     */
    public function pobierzDaneUzytkownika();

    /**
     * @param UploadInterface $upload
     * @return array
     */
    public function pobierzDaneWszystkichZapisanychZasobow(UploadInterface $upload);
}