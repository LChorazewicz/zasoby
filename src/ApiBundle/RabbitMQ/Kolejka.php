<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 23.06.18
 * Time: 20:32
 */

namespace ApiBundle\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Kolejka
{
    private $kanal;
    private $info;
    private $polaczenie;

    public function __construct($host, $port, $user, $password, $vhost)
    {
        $this->polaczenie = new AMQPStreamConnection(
            $host,
            $port,
            $user,
            $password,
            $vhost
        );
        $this->kanal = $this->polaczenie->channel();
    }

    public function podniesKolejke($nazwa, $exchange)
    {
        $this->kanal->queue_declare($nazwa, false, true, false, false);
        $this->kanal->exchange_declare($exchange, 'direct', false, true, false);
        $this->kanal->queue_bind($nazwa, $exchange);
        $this->info[] = "Dodałem kolejkę: " . $nazwa . " oraz exchange " . $exchange;
        return $this;
    }

    public function usunKolejke($nazwa, $exchange)
    {
        $this->kanal->exchange_delete($exchange);
        $this->kanal->queue_delete($nazwa);
        $this->info[] = "Usunalem kolejkę: " . $nazwa . " oraz exchange " . $exchange;
        return $this;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function dodajWiadomosc($wiadomosc, $exchange = '', $routingKey = '')
    {
        $this->kanal->basic_publish($wiadomosc, $exchange, $routingKey);
        return $this;
    }
}