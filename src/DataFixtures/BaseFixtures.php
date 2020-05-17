<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 categorys! Bam!
        for ($i = 0; $i < 20; $i++) {
            $category = new Category();
            $category->setLabel('category '.$i);
            $manager->persist($category);
        }

        $manager->flush();
    }
}