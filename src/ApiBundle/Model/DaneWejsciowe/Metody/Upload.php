<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.07.18
 * Time: 17:51
 */

namespace ApiBundle\Model\DaneWejsciowe\Metody;

use ApiBundle\Exception\NiepelneDaneException;
use ApiBundle\Model\DaneWejsciowe\DaneWejscioweAbstract;
use ApiBundle\Model\DaneWejsciowe\DaneWejscioweInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class Upload extends DaneWejscioweAbstract implements DaneWejscioweInterface
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
     * @return \ApiBundle\Entity\Uzytkownik
     */
    public function getDaneUzytkownika()
    {
        return $this->daneUzytkownika();
    }

    /**
     * @return \stdClass
     */
    public function getDaneWejsciowe()
    {
        return $this->daneWejsciowe();
    }

    /**
     * @return string
     */
    public static function getNazwaMetodyApi()
    {
        return 'Upload';
    }

    /**
     * @param \stdClass $daneWejsciowe
     * @return bool
     * @throws NiepelneDaneException
     */
    public function walidujDaneWejsciowe(\stdClass $daneWejsciowe): bool
    {
        $pliki = [];

        try{
            foreach ($daneWejsciowe->pliki as $plik) {
                $pliki[] = [
                    'pierwotna_nazwa' => $plik->pierwotna_nazwa,
                    'base64' => $plik->base64
                ];
            }

            if(array_search(null, $pliki, true) || empty($pliki)){
                throw new NiepelneDaneException();
            }
        }catch (NiepelneDaneException $daneException){
            throw new NiepelneDaneException();
        }catch (\Exception $exception){
            throw new NiepelneDaneException();
        }


        return true;
    }
}