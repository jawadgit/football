<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TeamControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Assert that the response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());

        // Example: Assert that there is at least one team listed
        $this->assertCount(5, $crawler->filter('tbody tr')); 
    }
}
