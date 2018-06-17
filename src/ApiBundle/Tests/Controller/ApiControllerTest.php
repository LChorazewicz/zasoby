<?php

namespace ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testUpload()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Upload');
    }

    public function testDownload()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Download');
    }

}
