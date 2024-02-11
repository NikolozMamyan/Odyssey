<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

      
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->word); 
            $manager->persist($category);

    
            $this->addReference('category_' . $i, $category);
        }
        
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [
            CourseFixture::class,
        ];
    }
}
