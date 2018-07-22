<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 20.07.18
 * Time: 23:18
 */

namespace ApiBundle\Model\DaneWejsciowe\Metody;


use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Exception\NiepelneDaneException;
use ApiBundle\Model\DaneWejsciowe\DaneWejscioweAbstract;
use ApiBundle\Model\DaneWejsciowe\DaneWejscioweInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Request;

class StrumienSzkic extends DaneWejscioweAbstract implements DaneWejscioweInterface
{
    /**
     * Upload constructor.
     * @param Request $request
     * @param Registry $doctrine
     */
    public function __construct(Request $request, Registry $doctrine)
    {
        parent::__construct($request, $doctrine);
        $this->walidujDaneWejsciowe($this->daneWejsciowe());
    }

    /**
     * @return \stdClass
     */
    public function getDaneWejsciowe()
    {
        return $this->daneWejsciowe();
    }

    /**
     * @return Uzytkownik
     */
    public function getDaneUzytkownika()
    {
        return $this->daneUzytkownika();
    }

    /**
     * @return string
     */
    public static function getNazwaMetodyApi()
    {
        return 'StrumienSzkic';
    }

    /**
     * @param \stdClass $daneWejsciowe
     * @return bool
     * @throws NiepelneDaneException
     */
    public function walidujDaneWejsciowe(\stdClass $daneWejsciowe): bool
    {
        try{
            $daneOczekiwane = [
                'pierwotna_nazwa' => $daneWejsciowe->dane_pliku->pierwotna_nazwa,
                'mime_type' => $daneWejsciowe->dane_pliku->pierwotna_nazwa,
                'rozmiar' => $daneWejsciowe->dane_pliku->pierwotna_nazwa,
            ];

            if(array_search(null, $daneOczekiwane, true)){
                throw new NiepelneDaneException();
            }

        }catch (NiepelneDaneException $exception){
            throw new NiepelneDaneException();
        }catch (\Exception $exception){
            throw new NiepelneDaneException();
        }

        return true;
    }
}