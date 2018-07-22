<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 20.07.18
 * Time: 23:10
 */

namespace ApiBundle\Model\Dane\Metody\Interfaces;


use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Repository\PlikRepository;
use ApiBundle\Repository\UzytkownikRepository;
use ApiBundle\Entity\Plik;

interface StrumienSzkicInterface
{
    /**
     * @return Uzytkownik
     */
    public function pobierzDaneUzytkownika();

    /**
     * @return array
     */
    public function getEncjaSzkicu();

    /**
     * @param PlikRepository $plikRepository
     * @param UzytkownikRepository $uzytkownikRepository
     * @param Plik $plik
     * @return bool
     */
    public function zapiszSzkicWBazie(PlikRepository $plikRepository, UzytkownikRepository $uzytkownikRepository, Plik $plik);

    /**
     * @return array
     */
    public function pobierzInformacjeZapisu(): array;
}