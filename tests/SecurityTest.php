<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Login');
    }

    public function testLoginWithBadCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertSelectorTextContains('h3', 'Login');
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'kmldskfmlskdf',
            'password' => 'fdgdfgdfgdfg'
        ]);

        $client->submit($form);
        // $this->assertResponseRedirects(401);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
        //  $this->assertResponseIsSuccessful();
    }

    public function testLoginWithGoodCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertSelectorTextContains('h3', 'Login');
        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate');
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'walidazzimani@gmail.com',
            'password' => 'sharingan.',
             '_csrf_token' => $csrfToken
        ]);

        $client->submit($form);
        $client->followRedirect();
        // $this->assertResponseRedirects('/profile');
        // $this->assertResponseRedirects('/profile');
        $this->assertSelectorNotExists('.alert.alert-danger');
        // $this->assertResponseIsSuccessful();
        // $this->assertResponseIsSuccessful();
    }

    // php bin/phpunit 

}
