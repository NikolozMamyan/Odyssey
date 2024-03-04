<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rolesData = ['etudiant', 'formateur', 'admin',];

        foreach ($rolesData as $roleType) {

            $role = new Role();
            $role->setTypeRole($roleType);
            $manager->persist($role);
        }

        $manager->flush();
    }
}
