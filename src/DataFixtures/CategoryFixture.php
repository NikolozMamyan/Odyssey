<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = ['HTML', 'CSS', 'JavaScript', 'PHP', 'Symfony'];

        foreach ($categories as $index => $categoryName) {

            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);

            $this->addReference('category_' . $index, $category);
        }

        $manager->flush();
    }

}
