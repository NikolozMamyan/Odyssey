<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CourseControllerTest extends WebTestCase
{

    public function testCourse(): void
    {
        // permet de simuler un navigateur
        $client = static::createClient();

        // Simuler l'authentification de l'utilisateur
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Valider')->form([
            'username' => 'nikoloz@gmail.com',
            'password' => 'azertyuioP1',
        ]);

        $client->submit($form);

        // Crée une requête HTTP GET pour accéder à la page d'édition d'utilisateur
        $client->request('GET', '/courses/create');

        // Vérifie que la réponse est un succès après la redirection
        $this->assertResponseIsSuccessful('La redirection vers la page de création d\'un cours à fonctionné.');

        // Insertion des données dans le formulaire
        $client->submitForm('Enregistrer', [
            'course[title]' => 'Cours sur le framework Symfony',
            'course[categories]' => [1, 2, 3, 4, 5],
            'course[description]' => 'Symfony, quand la magie opère',
            'course[content]' => 'Symfony, est un framework français écrit en php ....']);


        // Vérifie que la redirection vers la page de profil est correcte
        $this->assertResponseRedirects('/user');

        // Suivre la redirection vers la page de profil
        $client->followRedirect();

        // Vérifie que la réponse après la redirection est un succès
        $this->assertResponseIsSuccessful('Je suis sur la page de profil');

        // permet de vérifier que la modification est présente sur la page
        $this->assertSelectorExists('td:contains("Symfony, quand la magie opère")');
    }

}
