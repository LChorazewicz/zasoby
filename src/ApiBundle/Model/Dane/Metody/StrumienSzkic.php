<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 20.07.18
 * Time: 23:10
 */

namespace ApiBundle\Model\Dane\Metody;


use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Helper\DaneWejsciowe\Nazwa;
use ApiBundle\Library\Generuj;
use ApiBundle\Library\WarunkiBrzegowe\Plik;
use ApiBundle\Model\Dane\DaneAbstract;
use ApiBundle\Model\Dane\DaneInterface;
use ApiBundle\Model\Dane\Metody\Interfaces\StrumienSzkicInterface;
use ApiBundle\Repository\PlikRepository;
use ApiBundle\Repository\UzytkownikRepository;
use ApiBundle\Services\KontenerParametrow;

class StrumienSzkic extends DaneAbstract implements DaneInterface, StrumienSzkicInterface
{
    /**
     * @var \ApiBundle\Entity\Plik
     */
    private $encja;

    /**
     * StrumienSzkic constructor.
     * @param Uzytkownik $uzytkownik
     * @param \stdClass $daneWejsciowe
     * @param $nazwaMetodyApi
     * @param KontenerParametrow $kontenerParametrow
     */
    public function __construct(Uzytkownik $uzytkownik, \stdClass $daneWejsciowe, $nazwaMetodyApi, KontenerParametrow $kontenerParametrow)
    {
        parent::__construct($uzytkownik, $daneWejsciowe, $nazwaMetodyApi, $kontenerParametrow);
        $this->encja = $this->ustawEncjeSzkicu($daneWejsciowe, $uzytkownik);
    }

    /**
     * @return StrumienSzkicInterface
     */
    public function pobierz(): StrumienSzkicInterface
    {
        return $this;
    }

    /**
     * @return Uzytkownik
     */
    public function pobierzDaneUzytkownika()
    {
        return $this->daneUzytkownika();
    }

    /**
     * @return \ApiBundle\Entity\Plik
     */
    public function getEncjaSzkicu()
    {
        return $this->encja;
    }

    public function getKontenerParametrow()
    {
        return $this->kontenerParametrow();
    }

    public function zapiszSzkicWBazie(PlikRepository $plikRepository, UzytkownikRepository $uzytkownikRepository, \ApiBundle\Entity\Plik $plik)
    {
        if (!Plik::szkicPlikuKwalifikujeSieDoZapisu($plik, $plikRepository, $uzytkownikRepository)) {
            return false;
        }

        $plikRepository->zapiszPojedynczaEncjeWBazie($plik);
        return true;
    }

    /**
     * @param \stdClass $daneWejsciowe
     * @param Uzytkownik $uzytkownik
     * @return \ApiBundle\Entity\Plik
     */
    private function ustawEncjeSzkicu(\stdClass $daneWejsciowe, Uzytkownik $uzytkownik)
    {
        return (new \ApiBundle\Entity\Plik())
            ->setRozmiar($daneWejsciowe->dane_pliku->rozmiar)
            ->setIdZasobu(Generuj::UnikalnaNazwe())
            ->setCzyUsuniety(false)
            ->setDataDodania(new \DateTime())
            ->setMimeType($daneWejsciowe->dane_pliku->mime_type)
            ->setNazwaZasobu(Generuj::UnikalnaNazwe() . (new Nazwa($daneWejsciowe->dane_pliku->pierwotna_nazwa))
                ->getRozszerzenie())
            ->setPierwotnaNazwa($daneWejsciowe->dane_pliku->pierwotna_nazwa)
            ->setUzytkownikDodajacy($uzytkownik->getId())
            ->setSciezka('')
            ->setSzkic(true);
    }

    /**
     * @return array
     */
    public function pobierzInformacjeZapisu(): array
    {
        $encja = $this->getEncjaSzkicu();
        return [
            'idZasobu' => $encja->getIdZasobu(),
            'pierwotnaNazwa' => $encja->getPierwotnaNazwa()
        ];
    }

    /**
     * @return array
     */
    public function pobierzKolekcjeEncji(): array
    {
        return [
            $this->encja
        ];
    }
}