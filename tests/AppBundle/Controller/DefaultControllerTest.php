<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Clean Blog', $crawler->filter('.site-heading')->text());
    }

    public function testCreate()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/new');
        $text = $crawler->eq(0)->children()->eq(1)->eq(0)->children()->eq(0)->text();
        //$response = $client->getResponse();
        $this->assertContains('Вход', $text);
    }
}
