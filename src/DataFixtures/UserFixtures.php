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
        $faker = Factory::create('FR_fr');

        for ($i = 1; $i <= self::USER_COUNT; $i++) {
            $user = new User();

            $user->setEmail($faker->email())
                ->setPseudo($faker->word())
                ->setPassword('C0nvertit!')
                ->setBio($faker->paragraph(2, true))
                ->setProfilePicture('https://source.unsplash.com/random/80x80')
                ->setRole('user')
                ->setActive(true);
            $manager->persist($user);
            $this->addReference(self::PREFIX . $i, $user);
        }
        $user = new User();

        $user->setEmail($faker->email())
            ->setPseudo('admin')
            ->setPassword('C0nvertit!')
            ->setBio($faker->paragraph(2, true))
            ->setProfilePicture('https://source.unsplash.com/random/80x80')
            ->setRole('admin')
            ->setActive(true);
        $manager->persist($user);
        $this->addReference(self::PREFIX .  (self::USER_COUNT + 1), $user);

        $manager->flush();
    }
}
