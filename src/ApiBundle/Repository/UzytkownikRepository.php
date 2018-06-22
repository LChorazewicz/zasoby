<?php

namespace ApiBundle\Repository;
use ApiBundle\Entity\Plik;
use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Exception\UzytkownikNieIstniejeException;
use ApiBundle\Exception\ZasobNieIstniejeException;

/**
 * UzytkownikRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UzytkownikRepository extends \Doctrine\ORM\EntityRepository
{
    public function czyIstniejeTakiUzytkownik($login)
    {
        $encja = $this->findOneBy(['login' => $login]);

        if(!$encja instanceof Uzytkownik){
            throw new UzytkownikNieIstniejeException();
        }

        return ($encja->getId()) ? true : false;
    }

    public function pobierzIdUzytkownikaPoLoginie($login, $haslo)
    {
        /**
         * @var $id Uzytkownik
         */
        $encja = $this->findOneBy(['login' => $login, 'haslo' => $haslo]);

        if(!$encja instanceof Uzytkownik){
            throw new UzytkownikNieIstniejeException();
        }

        return $encja->getId();
    }

    public function czyUzytkownikMozeUsunacZasob($id_uzytkownik, $id_zasobu)
    {
        /**
         * @var $id Uzytkownik
         */
        $uzytkownik = $this->findOneBy(['id' => $id_uzytkownik]);

        if(!$uzytkownik instanceof Uzytkownik){
            throw new UzytkownikNieIstniejeException();
        }

        $zasob = $this->getEntityManager()->getRepository(Plik::class)->findOneBy(['idZasobu' => $id_zasobu]);

        if(!$zasob instanceof Plik){
            throw new ZasobNieIstniejeException();
        }

        return ($zasob->getUzytkownikDodajacy() === $uzytkownik->getId());
    }
}
