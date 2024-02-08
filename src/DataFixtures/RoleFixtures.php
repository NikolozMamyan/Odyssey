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
        $roleAdmin = new Role();

        $roleStudent->setTypeRole('student');
        $roleTeacher->setTypeRole('teacher');
        $roleAdmin->setTypeRole('admin');

        $manager->persist($roleStudent);
        $manager->persist($roleTeacher);
        $manager->persist($roleAdmin);

        $manager->flush();
    }
}
