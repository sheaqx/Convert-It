<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public const USER_COUNT = 20;
    public const PREFIX = 'user_';


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= self::USER_COUNT; $i++) {
            $user = new User();

            $user->setEmail($faker->email())
                ->setPseudo($faker->firstName())
                ->setBio($faker->paragraph(2, true))
                ->setProfilePicture('https://source.unsplash.com/random/80x80')
                ->setRoles(['ROLE_USER'])
                ->setActive(true)
                ->setPlainPassword('C0nvertIt!');
            $manager->persist($user);
            $this->addReference(self::PREFIX . $i, $user);
        }
        $user = new User();

        $user->setEmail($faker->email())
            ->setPseudo('admin')
            ->setBio($faker->paragraph(2, true))
            ->setProfilePicture('https://source.unsplash.com/random/80x80')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setActive(true)
            ->setPlainPassword('C0nvertIt!');
        $manager->persist($user);
        $this->addReference(self::PREFIX .  (self::USER_COUNT + 1), $user);

        $manager->flush();
    }
}
