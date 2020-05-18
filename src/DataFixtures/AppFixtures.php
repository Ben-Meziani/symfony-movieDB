<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\Person;
use DateInterval;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");

        $categoryList = [];
        $categories = ["Science-fiction", "Aventure", "Thriller", "Horreur", "Indé", "Animation"];
        foreach ($categories as $catgeoryName) {
            $category = new Category();
            $category->setLabel($catgeoryName);
            $categoryList[] = $category;
            $manager->persist($category);
        }

        $personList = [];
        for ($i = 0; $i < 200; $i++) {
            $person = new Person();
            $person->setName($faker->name());
            $person->setBirthDate($faker->dateTimeBetween("-120 years"));
            $personList[] = $person;
            $manager->persist($person);
        }


        for ($i = 0; $i < 20; $i++) {
            $movie = new Movie();
            $movie->setTitle($faker->catchPhrase);

            $director = $personList[mt_rand(0, count($personList) - 1)];
            $movie->setDirector($director);


            $movie->setReleaseDate(
                $faker->dateTimeBetween(
                    $director->getBirthDate()->add(new DateInterval('P18Y'))
                )
            );
            // entre 1 et 3 cat
            for ($j = 0; $j < mt_rand(1, 3); $j++) {
                // je recup une catgorie au hazard
                $category = $categoryList[mt_rand(0, count($categoryList) - 1)];
                // je l'ajoute au film
                if (!$movie->getCategories()->contains($category)) {
                    $movie->addCategory($category);
                }
            }

            $manager->persist($movie);
        }

        $manager->flush();
    }
}
