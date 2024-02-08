<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $roleStudent = new Role();
        $roleTeacher = new Role();

        $roleStudent->setTypeRole('student');
        $roleTeacher->setTypeRole('teacher');

        $manager->persist($roleStudent);
        $manager->persist($roleTeacher);

        $manager->flush();
    }
}
