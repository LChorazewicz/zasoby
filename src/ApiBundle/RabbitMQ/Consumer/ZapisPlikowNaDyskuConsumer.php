<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 12.07.18
 * Time: 01:11
 */

namespace ApiBundle\RabbitMQ\Consumer;


use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class ZapisPlikowNaDyskuConsumer implements ConsumerInterface
{
    public function __construct()
    {
        echo "ZapisPlikowNaDyskuService - nasłuchuje";
    }

    /**
     * @param AMQPMessage $msg The message
     * @return mixed false to reject and requeue, any other value to acknowledge
     */
    public function execute(AMQPMessage $msg)
    {
    }
}