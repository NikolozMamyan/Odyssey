<?php

namespace App\DataFixtures;

use App\Entity\Teacher;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;


class TeacherFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $teacher = new Teacher();
            $teacher
                ->setName($faker->name)
                ->setEmail($faker->email);
            $manager->persist($teacher);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['course-teacher'];
    }
}
