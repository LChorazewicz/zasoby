<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 20.07.18
 * Time: 20:16
 */

namespace ApiBundle\RabbitMQ\Producer;


use ApiBundle\RabbitMQ\Kolejka;
use ApiBundle\RabbitMQ\Producer\Helper\WysylkaEmailowHelper;
use ApiBundle\RabbitMQ\Producer\Interfaces\WyslijEmailaInterface;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use ApiBundle\Library\Stale\Kolejka as KolejkaStale;

class EmailProducer implements WyslijEmailaInterface
{
    /**
     * @var AMQPMessage
     */
    private $wiadomosc;

    /**
     * @var Kolejka
     */
    private $kolejka;

    /**
     * WyslijEmaila constructor.
     * @param WysylkaEmailowHelper $wysylkaEmailowHelper
     */
    public function __construct(WysylkaEmailowHelper $wysylkaEmailowHelper)
    {
        $wiadomosc = new AMQPMessage(json_encode([
            'nadawca' => $wysylkaEmailowHelper->getNadawca(),
            'odbiorca' => $wysylkaEmailowHelper->getOdbiorca(),
            'temat' => $wysylkaEmailowHelper->getTemat(),
            'wiadomosc' => $wysylkaEmailowHelper->getWiadomosc()
        ]), [
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            'application_headers' => new AMQPTable([
                'x-delay' => 2000
            ])
        ]);
        $this->wiadomosc = $wiadomosc;
        $this->kolejka = $wysylkaEmailowHelper->getKolejka();
    }

    public function wyslij(): void
    {
        $this->kolejka->dodajWiadomosc($this->wiadomosc, KolejkaStale::EMAIL, '');
    }
}