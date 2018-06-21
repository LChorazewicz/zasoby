<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 21.06.18
 * Time: 18:47
 */

namespace ApiBundle\Utils;


use Symfony\Component\DependencyInjection\ContainerInterface;

class Mailer
{
    private $container;

    /**
     * Mailer constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $temat
     * @param $odKogo
     * @param $doKogo
     * @param $wiadomosc
     */
    final public function wyslijEmaila($temat, $odKogo, $doKogo, $wiadomosc)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($temat)
            ->setFrom($odKogo)
            ->setTo($doKogo)
            ->setBody($wiadomosc, 'text/html');
        $this->container->get('mailer')->send($message);
    }
}