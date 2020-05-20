<?php

namespace App\DataFixtures;

use App\Entity\Property;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PropertyFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $user1 = new User();
        $user1->setEmail('seveste.brandon@gmail.com')->setPassword($this->encoder->encodePassword($user1, 'admin'))->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $manager->persist($user1);

        $users[] = $user1;
        for ($i = 0; $i <= 15; $i++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $users[] = $user;
        }
        for ($i = 0; $i <= 25; $i++) {
            $property = new Property();
            $property->setTitle($faker->sentence('2'))
                ->setCoverimg($faker->imageUrl('200', '100'))
                ->setDescription($faker->text('200'))
                ->setContent($faker->text('400'))
                ->setPrice($faker->randomNumber('6'))
                ->setRoom($faker->numberBetween('2', '5'))
                ->setCreatedAt(new DateTime())
                ->setUser($faker->randomElement($users))
                ->setReference(mt_rand(10000, 99999));

            $manager->persist($property);
        }

        $manager->flush();
    }
}
