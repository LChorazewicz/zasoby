<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 23.06.18
 * Time: 15:10
 */

namespace ApiBundle\Model\RabbitMQ;


use ApiBundle\Model\Mailer;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class EmailConsumer implements ConsumerInterface
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
        echo "EmailConsumer - nasłuchuje";
    }

    /**
     * @param AMQPMessage $msg The message
     * @return mixed false to reject and requeue, any other value to acknowledge
     */
    public function execute(AMQPMessage $msg)
    {
        $dane = json_decode($msg->body);
        return $this->mailer->wyslijEmaila($dane->temat, $dane->nadawca, $dane->odbiorca, $dane->wiadomosc);
    }
}