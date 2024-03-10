<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * Tests login and user can modify his information
     *
     * @return void
     */
    public function testUserEdit(): void
    {
        // Enables simulating a browser.
        $client = static::createClient();

        // Simulating user authentication.
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Valider')->form([
            'username' => 'test@test.com',
            'password' => 'azertyP1',
        ]);

        $client->submit($form);


        // Crée une requête HTTP GET pour accéder à la page d'édition d'utilisateur
        $client->request('GET', '/user/edit/5');


        // Verifies that the response is successful after redirection.
        $this->assertResponseIsSuccessful('La redirection vers la page de modification de profil à fonctionné.');

        // Inserts data into the form
        $client->submitForm('user_edit_save', [
            'user_edit[firstNameUser]' => 'Fabien',
            'user_edit[lastNameUser]' => 'Potencier']);


        $client->followRedirect();

        // Verifies that the destination page after modification is displayed.
        $this->assertResponseIsSuccessful();

        // Enables verifying that the modification is present on the page.
        $this->assertSelectorExists('p:contains("Potencier")');


    }
}
