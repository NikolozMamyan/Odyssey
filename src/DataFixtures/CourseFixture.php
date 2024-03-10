<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\String\Slugger\SluggerInterface;


class CourseFixture extends Fixture implements DependentFixtureInterface
{
    private const NUMBER_OF_COURSE = 10;

    private Generator $faker;
    private UserRepository $userRepository;
    private SluggerInterface $slugger;

    public function __construct(UserRepository $userRepository, SluggerInterface $slugger)
    {
        $this->faker = Factory::create('fr_FR');
        $this->userRepository = $userRepository;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [
            $this->getReference('category_0'),
            $this->getReference('category_1'),
            $this->getReference('category_2'),
            $this->getReference('category_3'),
            $this->getReference('category_4'),
        ];

        $users = $this->userRepository->findAll();

        foreach ($users as $user) {

            $roleUser = $user->getRoleUser();

            if ($roleUser->getTypeRole() === 'formateur') {

                for ($i = 0; $i < self::NUMBER_OF_COURSE; $i++) {

                    $course = (new Course())
                        ->setTitle($this->faker->realText(10))
                        ->setDescription($this->faker->realText(100))
                        ->setContent($this->faker->realText(1500))
                        ->setCreatedBy($user)
                        ->setStatus('Validé');

                    $slug = $this->slugger->slug($course->getTitle());

                    $course->setSlug($slug);

                    $randomCategories = $this->faker->randomElements($categories, rand(1, 3));
                    foreach ($randomCategories as $category) {
                        $course->addCategory($category);
                    }

                    $manager->persist($course);
                }
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
            UserFixtures::class,
            CategoryFixture::class,
        ];
    }
}


