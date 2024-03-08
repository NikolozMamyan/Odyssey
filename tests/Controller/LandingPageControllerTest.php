<?php

namespace App\Tests\Controller;

use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LandingPageControllerTest extends WebTestCase
{

    /**
     * Tests accessing the course page and verifies that there is at least one course present.
     *
     * @return void
     */
    public function testIndex(): void
    {
        // Enables simulating a browser.
        $client = static::createClient();

        // Returns an instance of Crawler.
        $crawler = $client->request('GET','/');

        // Tests that there is 1 HTML tag using the selector.
        $this->assertCount(1, $crawler->filter('h1'));

        // Enables simulating a click on a link.
        $client->clickLink('Consulter les cours');

        // Verifies the response status
        $this->assertResponseIsSuccessful();

        // Verifies the title of the page.
        $this->assertPageTitleContains('Liste des cours !');

        // Verifies the content of the h1 tag
        $this->assertSelectorTextContains('h1', "LES COURS D'ODYSSEY");

        // Verifies that there is a course on the page.
        $course = new Course();
        $this->assertSelectorExists('div:contains("' . $course->getTitle() . '")');

    }

}