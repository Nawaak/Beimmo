<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
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

        $user = new User();
        $user->setEmail('seveste.brandon@gmail.com')->setPassword($this->encoder->encodePassword($user,'admin'))->setRoles(['ROLE_ADMIN','ROLE_USER']);
        $manager->persist($user);

        for($i=0; $i <= 15; $i++){
           $user = new User();
           $user->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user,'password'))
                ->setRoles(['ROLE_USER']);
           $manager->persist($user);
        }

        $manager->flush();
    }
}
