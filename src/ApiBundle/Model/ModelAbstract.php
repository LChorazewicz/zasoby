<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 08.07.18
 * Time: 12:13
 */

namespace ApiBundle\Model;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ModelAbstract extends Controller
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function getParameter($nazwa)
    {
        return $this->container->getParameter($nazwa);
    }
}