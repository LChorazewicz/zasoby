<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class KolejkiControllerController
 * @package ApiBundle\Controller
 */
class KolejkiController extends FOSRestController
{
    /**
     * @Route("/kolejki/restart/{nazwaKolejki}", defaults={"nazwaKolejki"="wszystkie"})
     * @param Request $request
     * @return Response
     */
    public function getRestartAction(Request $request)
    {
        $kolejkaService = $this->get('api.kolejki');

        /**
         * todo: zrobic wczytywanie z bazy
         * todo: zrobic jako komende
         * todo: zrobic builder restartu kolejek
         */
        $kolejki = [
            ['nazwa' => 'kolejka_email', 'exchange' => 'kolejka_email'],
            ['nazwa' => 'kolejka_zapis_plikow', 'exchange' => 'kolejka_zapis_plikow']
        ];

        try{
            foreach ($kolejki as $kolejka) {
                $kolejkaService->usunKolejke($kolejka['nazwa'], $kolejka['exchange'])
                    ->podniesKolejke($kolejka['nazwa'], $kolejka['exchange']);
            }
        }catch (\Exception $exception){
            foreach ($kolejki as $kolejka) {
                $kolejkaService->podniesKolejke($kolejka['nazwa'], $kolejka['exchange']);
            }
        }

        return $this->handleView($this->view([
            $kolejkaService->getInfo()
        ], Response::HTTP_OK));
    }

    /**
     * @Route("/kolejki/dodajwiadomosc", methods={"GET"})
     * @return Response
     * @internal param Request $request
     */
    public function getDodajWiadomoscAction()
    {
        $kolejka = $this->get('api.kolejki');

        $msg = [
            'temat' => "asd",
            'odbiorca' => "",
            'nadawca' => "",
            'wiadomosc' => "test kolejki",
        ];

        $wiadomosc = new AMQPMessage(json_encode($msg), [
            'content_type' => 'text/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            'application_headers' => new AMQPTable([
                'x-delay' => 2000
            ])
        ]);

        $kolejka->dodajWiadomosc($wiadomosc, 'kolejka_email');

        return $this->handleView($this->view([
            'status' => 1,
            'zasoby' => 1
        ], Response::HTTP_CREATED));
    }
}
