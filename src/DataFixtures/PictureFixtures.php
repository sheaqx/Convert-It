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
                $picture->setName('https://source.unsplash.com/random/300x300')
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
