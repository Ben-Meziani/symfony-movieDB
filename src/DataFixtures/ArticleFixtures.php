<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // create 20 categorys! Bam!
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setLabel($faker->sentence());
            $manager->persist($category);
        }

        $manager->flush();
    }
}