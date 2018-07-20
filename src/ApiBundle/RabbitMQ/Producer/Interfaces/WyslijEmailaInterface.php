<?php
/**
 * Created by PhpStorm.
 * User: leszek
 * Date: 20.07.18
 * Time: 20:18
 */

namespace ApiBundle\RabbitMQ\Producer\Interfaces;

use ApiBundle\RabbitMQ\Producer\Helper\WysylkaEmailowHelper;

interface WyslijEmailaInterface
{
    /**
     * WyslijEmailaInterface constructor.
     * @param WysylkaEmailowHelper $wysylkaEmailowHelper
     */
    public function __construct(WysylkaEmailowHelper $wysylkaEmailowHelper);

    public function wyslij(): void;
}