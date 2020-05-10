<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PropertyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i=0; $i <= 25; $i++){
            $property = new Property();
            $property->setTitle($faker->sentence('2'))
                ->setCoverimg($faker->imageUrl('200','100'))
                ->setDescription($faker->text('200'))
                ->setContent($faker->text('400'))
                ->setPrice($faker->randomNumber('6'))
                ->setRoom($faker->numberBetween('2','5'))
                ->setCreatedAt(new \DateTime());
            $manager->persist($property);
        }

        $manager->flush();
    }
}
