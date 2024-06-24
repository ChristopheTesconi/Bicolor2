<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($u = 0; $u < 10; $u++) {
            $user = new User;
            $user->setEmail($faker->unique()->safeEmail());
            // $user->setRoles($faker->shuffle(array('admin, user')));
            $plaintextPassword = '1234';
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);
            $user->setName($faker->unique()->name());
            $user->setScores($faker->numberBetween(10, 45000));
            // $user->setComments($faker->text(50));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
