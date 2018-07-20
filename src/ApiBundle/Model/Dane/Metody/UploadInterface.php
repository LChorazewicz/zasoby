<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.07.18
 * Time: 23:09
 */

namespace ApiBundle\Model\Dane\Metody;


use ApiBundle\Entity\Uzytkownik;
use ApiBundle\RabbitMQ\Kolejka;
use ApiBundle\Services\KontenerParametrow;

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

    /**
     * @param Kolejka $kolejka
     * @param string $wiadomosc
     * @return void
     */
    public function wyslijEmailaPoZakonczeniuUploadu(Kolejka $kolejka, string $wiadomosc);
}