<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Teacher;
use App\Repository\TeacherRepository;
use Doctrine\Persistence\ObjectManager;;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CourseFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private TeacherRepository $teacherRepository;
    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        $teachers = $this->teacherRepository->findAll();
        foreach ($teachers as $teacher) {
            $randCourseNumber = rand(0, 3);
            for ($i = 0; $i < $randCourseNumber; $i++) {
                $course = (new Course())
                    ->setTitle($faker->sentence)
                    ->setDescription($faker->text)
                    ->setAuthor($teacher)
                    ->setContent($faker->text(10000));
                $manager->persist($course);
            }
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['course-teacher'];
    }
    public function getDependencies(): array
    {
        return [
            TeacherFixture::class,
        ];
    }
}
