<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\Plik;
use ApiBundle\Exception\ZasobNieIstniejeException;
use ApiBundle\Library\Helper\DaneWejsciowe\DaneWejscioweUpload;
use ApiBundle\Library\Helper\DaneWejsciowe\EncjaPlikuNaPoziomieDanychWejsciowych;
use ApiBundle\Library\Helper\EncjaPliku;
use ApiBundle\Model\FizycznyPlik;
use ApiBundle\Model\PrzetworzDane;

/**
 * PlikRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlikRepository extends \Doctrine\ORM\EntityRepository
{
    public function pobierzSciezkeDoZasobu($zasob)
    {
        $encja = $this->findOneBy(['idZasobu' => $zasob, 'czyUsuniety' => false]);

        if (!$encja instanceof Plik) {
            throw new ZasobNieIstniejeException();
        }

        return $encja->getSciezka();
    }

    /**
     * @param $idZasobu
     * @param $elementyDoZmiany
     */
    public function zmodyfikujZasob($idZasobu, $elementyDoZmiany)
    {
        $managerEncji = $this->getEntityManager();
        /**
         * @var $encja Plik
         */
        $encja = $this->findOneBy(['idZasobu' => $idZasobu, 'czyUsuniety' => false]);

        $elementyDoZmiany = array_filter($elementyDoZmiany);

        if (isset($elementyDoZmiany['pierwotna_nazwa'])) {
            $encja->setPierwotnaNazwa($elementyDoZmiany['pierwotna_nazwa']);
        }
        if (isset($elementyDoZmiany['czy_usuniety'])) {
            $encja->setCzyUsuniety($elementyDoZmiany['czy_usuniety']);
        }

        $managerEncji->persist($encja);
        $managerEncji->flush();
    }

    /**
     * @param $daneWejsciowe
     */
    public function zapiszInformacjeOPlikuWBazie(DaneWejscioweUpload $daneWejsciowe): void
    {
        $managerEncji = $this->getEntityManager();

        /**
         * @var $danePliku EncjaPlikuNaPoziomieDanychWejsciowych
         */
        foreach ($daneWejsciowe->getKolekcjaPlikow() as $danePliku) {
            $encjaPliku = new Plik();
            $przetworzDane = PrzetworzDane::uzupelnijEncjePliku($encjaPliku, $danePliku->getEncjaPliku(), $daneWejsciowe->getDaneUzytkownika());
            $managerEncji->persist($encjaPliku);
            $managerEncji->flush();
        }
    }

    public function usunMiekkoPlik($id_zasobu)
    {
        $managerEncji = $this->getEntityManager();

        try {
            $zasob = $this->findOneBy(['idZasobu' => $id_zasobu]);

            /**
             * @var $zasob Plik
             */

            $zasob->setCzyUsuniety(true);
            $managerEncji->persist($zasob);
            $managerEncji->flush();
        } catch (\Exception $exception) {

        }
        return true;
    }
}
