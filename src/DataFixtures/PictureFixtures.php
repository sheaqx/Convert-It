<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public const TOTAL_USERS = (UserFixtures::USER_COUNT + 1);
    public const USER_PICTURES = 5;
    public const TAGS = [
        'nature',
        'sport',
        'détente',
        'animaux',
        'plantes',
        'architecture',
        'art',
        'loisirs',
        'vintage',
        'moderne',
        'création'
    ];

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= self::TOTAL_USERS; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $picture = new Picture();
                $picture->setName('https://images.unsplash.com/photo-1668763118304-3f8525fcc92d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80')
                    ->setDescription($faker->paragraph(1, true))
                    ->setTag($faker->randomElement(self::TAGS))
                    ->setSlug($picture->getName())
                    ->setUser($this->getReference(UserFixtures::PREFIX . $i));
                $manager->persist($picture);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
