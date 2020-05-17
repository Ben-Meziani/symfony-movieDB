<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // create 20 categorys! Bam!
        for ($i = 0; $i < 10; $i++) {
            $movie = new Movie();
            $movie->setTitle($faker->Name ())
                  ->setReleaseDate($faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null)());
            $manager->persist($movie);
        }

        $manager->flush();
    }
}