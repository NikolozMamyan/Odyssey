<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests unitaires pour le contrôleur RegistrationController.
 */
class RegistrationControllerTest extends WebTestCase
{
    /**
     * Teste le processus d'inscription d'un nouvel utilisateur.
     * Vérifie que le formulaire d'inscription peut être soumis avec succès.
     */
    public function testRegistration(): void
    {
        // Crée un client HTTP pour simuler une requête
        $client = static::createClient();

        // Envoie une requête GET sur la route '/register' pour afficher le formulaire d'inscription
        $crawler = $client->request('GET', '/register');

        // Sélectionne le bouton "S'inscrire" et remplit le formulaire avec des données de test
        $form = $crawler->selectButton("S'inscrire")->form([
            'registration_form[firstNameUser]' => 'test1',
            'registration_form[lastNameUser]' => 'test2',
            'registration_form[email]' => 'test@test.com',
            'registration_form[plainPassword][first]' => 'azertyP1',
            'registration_form[plainPassword][second]' => 'azertyP1',
            'registration_form[student]' => '1',
            'registration_form[teacher]' => '1',
            'registration_form[agreeTerms]' => '1',
        ]);

        // Soumet le formulaire d'inscription rempli
        $client->submit($form);

        // Vérifie que la réponse du contrôleur est un succès (code de statut HTTP 2xx)
        $this->assertResponseIsSuccessful();
    }
}