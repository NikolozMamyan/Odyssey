<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUserEdit(): void
    {
        // permet de simuler un navigateur
        $client = static::createClient();

        // Simuler l'authentification de l'utilisateur
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Valider')->form([
            'username' => 'test@test.com',
            'password' => 'azertyP1',
        ]);

        $client->submit($form);


        // Crée une requête HTTP GET pour accéder à la page d'édition d'utilisateur
        $client->request('GET', '/user/edit/5');

        // Vérifie que la réponse est un succès après la redirection
        $this->assertResponseIsSuccessful('La redirection vers la page de modification de profil à fonctionné.');

        // Insertion des données dans le formulaire
        $client->submitForm('user_edit_save', [
                            'user_edit[firstNameUser]' => 'Fabien',
                            'user_edit[lastNameUser]' => 'Potencier']);


        $client->followRedirect();

        // Vérifie que la page de destination après la modification est affichée
        $this->assertResponseIsSuccessful();

        // permet de vérifier que la modification est présente sur la page
         $this->assertSelectorExists('p:contains("Potencier")');

    }
}
