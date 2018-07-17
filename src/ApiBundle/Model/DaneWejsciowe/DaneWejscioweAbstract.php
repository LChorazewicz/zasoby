<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 16.07.18
 * Time: 18:03
 */

namespace ApiBundle\Model\DaneWejsciowe;


use ApiBundle\Entity\Uzytkownik;
use ApiBundle\Exception\UzytkownikNieIstniejeException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Request;

class DaneWejscioweAbstract implements DaneWejscioweAbstractInterface
{
    /**
     * @var $daneWejsciowe \stdClass
     */
    private $daneWejsciowe;

    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * DaneWejscioweAbstract constructor.
     * @param Request $request
     * @param Registry $doctrine
     */
    public function __construct(Request $request, Registry $doctrine)
    {
        $this->daneWejsciowe = $this->parsujDaneWejscioweDoObiektu($request->getContent());
        $this->doctrine = $doctrine;
    }

    /**
     * @return Uzytkownik
     * @throws UzytkownikNieIstniejeException
     */
    public final function daneUzytkownika()
    {
        $uzytkownikRepository = $this->doctrine->getRepository(Uzytkownik::class);

        $login = $this->daneWejsciowe->login;
        $haslo = $this->daneWejsciowe->haslo;

        try{
            $uzytkownik = $uzytkownikRepository->pobierzDaneUzytkownikaPoLoginieIHasle($login, $haslo);
        }catch (UzytkownikNieIstniejeException $uzytkownikNieIstniejeException){
            throw new UzytkownikNieIstniejeException("Próba nieautoryzowanego dostępu do api [login: " . $login . ", hasło: " . $haslo . "]");
        }

        return $uzytkownik;
    }

    /**
     * @return \stdClass
     */
    public final function daneWejsciowe()
    {
        return $this->daneWejsciowe;
    }

    /**
     * @param $daneWejscioweJson
     * @return \stdClass
     */
    private final function parsujDaneWejscioweDoObiektu($daneWejscioweJson){
        return json_decode($daneWejscioweJson);
    }
}