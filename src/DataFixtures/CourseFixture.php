<?php
namespace App\DataFixtures;

use App\Entity\Course;
use App\Repository\TeacherRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Faker\Generator;


class CourseFixture extends Fixture implements DependentFixtureInterface
{
    private TeacherRepository $teacherRepository;
    private Generator $faker;

    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
        $this->faker = Factory::create('fr_FR');
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
        
        $teachers = $this->teacherRepository->findAll();
        
        foreach ($teachers as $teacher) {
            $randCourseNumber = rand(0, 3);
            
            for ($i = 0; $i < $randCourseNumber; $i++) {
                $course = (new Course())
                    ->setTitle($this->faker->realText(10))
                    ->setDescription($this->faker->realText(100))
                    ->setAuthor($teacher)
                    ->setContent($this->faker->realText(1500));
                
            $randomCategories = $this->faker->randomElements($categories, rand(1, 3));
            foreach ($randomCategories as $category) {
                $course->addCategory($category);
            }

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
            CategoryFixture::class,
        ];
    }
}


