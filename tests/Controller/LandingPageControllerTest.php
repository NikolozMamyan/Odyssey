<?php

namespace App\Tests\Controller;

use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LandingPageControllerTest extends WebTestCase
{

    public function testIndex(): void
    {
        // permet de simuler un navigateur
        $client = static::createClient();

        // retourne une instance de Crawler
        $crawler = $client->request('GET','/');

        // test qu'il y a 1 balise html grâce au sélecteur
        $this->assertCount(1, $crawler->filter('h1'));

        // permet de simuler un click sur un lien
        $client->clickLink('Consulter les cours');

        // vérifie le status de la réponse
        $this->assertResponseIsSuccessful();

        // vérifie le titre de la page
        $this->assertPageTitleContains('Liste des cours !');

        // vérifie le contenu de la balise h1
        $this->assertSelectorTextContains('h1', "LES COURS D'ODYSSEY");

        // permet de vérifier qu'il y a un cours sur la page
        $course = new Course();
        $this->assertSelectorExists('div:contains("' . $course->getTitle() . '")');

    }

}