<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CourseControllerTest extends WebTestCase
{

    /**
     * Tests login and create a new course
     *
     * @return void
     */
    public function testCourse(): void
    {
        // Allows to simulate a browser
        $client = static::createClient();

        // To simulate user authentication
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Valider')->form([
            'username' => 'nikoloz@gmail.com',
            'password' => 'azertyuioP1',
        ]);

        $client->submit($form);

        // Creates an HTTP GET request to access the user editing page
        $client->request('GET', '/courses/create');

        // Verifies that the response is a success after redirection.
        $this->assertResponseIsSuccessful('La redirection vers la page de création d\'un cours à fonctionné.');

        // Inserting data into the form.
        $client->submitForm('Enregistrer', [
            'course[title]' => 'Cours sur le framework Symfony',
            'course[categories]' => [1, 2, 3, 4, 5],
            'course[description]' => 'Symfony, quand la magie opère',
            'course[content]' => 'Symfony, est un framework français écrit en php ....']);


        // Verifies that the redirection to the profile page is correct.
        $this->assertResponseRedirects('/user');

        // Follow the redirection to the profile page.
        $client->followRedirect();

        // Verifies that the response after redirection is successful
        $this->assertResponseIsSuccessful('Je suis sur la page de profil');

        // Allows to verify that the modification is present on the page
        $this->assertSelectorExists('td:contains("Symfony, quand la magie opère")');
    }

}