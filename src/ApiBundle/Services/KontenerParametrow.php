<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 11.07.18
 * Time: 17:25
 */

namespace ApiBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

class KontenerParametrow
{
    private $container = null;
    /**
     * KontenerParametrow constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function pobierzParametrZConfigu($nazwa)
    {
        return $this->container->getParameter($nazwa);
    }
}