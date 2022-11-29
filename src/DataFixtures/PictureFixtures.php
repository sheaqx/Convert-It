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
    public const PICTURES_NAME = [
        "files/pictures/allGood.png",
        "files/pictures/avion.png",
        "files/pictures/campagne.jpg",
        "files/pictures/canard.jpg",
        "files/pictures/cuisine.jpg",
        "files/pictures/foret.jpg",
        "files/pictures/fun.jpg",
        "files/pictures/lac.webp",
        "files/pictures/lapin.webp",
        "files/pictures/maison.png",
        "files/pictures/montagne.jpg",
        "files/pictures/moto.png",
        "files/pictures/neige.webp",
        "files/pictures/pluie.png",
        "files/pictures/television.png",
        "files/pictures/tomates.jpg",
        "files/pictures/velo.webp",
        "files/pictures/voiture.webp"
    ];


    public function load(ObjectManager $manager): void
    {
        $fileList = glob('public/files/pictures/*');
        // dd($fileList);
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= self::TOTAL_USERS; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $picture = new Picture();
                $picture->setDescription($faker->paragraph(1, true))
                    ->setTag($faker->randomElement(self::TAGS))
                    ->setSlug($faker->randomElement(str_replace('public/files/pictures/', '', $fileList)))
                    ->setName($picture->getSlug())
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
