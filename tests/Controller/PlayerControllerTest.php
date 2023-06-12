<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/player');

        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());

        // Assert that the pagination is working correctly
        $this->assertCount(5, $crawler->filter('tbody tr')); 
    }
}
