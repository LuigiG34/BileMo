<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
use App\Entity\Product;
use App\Entity\Client;
use App\Entity\User;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Data Fixtures for User 
        $orange = new User();
        $orange->setEmail('user@orange.fr');
        $orange->setName('Orange Telecom');
        $orange->setRoles(["ROLE_USER"]);
        $orange->setPassword($this->userPasswordHasher->hashPassword($orange, "passwordOrange"));
        $manager->persist($orange);

        // Data Fixtures for User 
        $sfr = new User();
        $sfr->setEmail('user@sfr.fr');
        $sfr->setName('SFR Telecom');
        $sfr->setRoles(["ROLE_USER"]);
        $sfr->setPassword($this->userPasswordHasher->hashPassword($sfr, "passwordSFR"));
        $manager->persist($sfr);

        // Data Fixtures for Products, here iPhones
        for($i = 1; $i < 16; $i++) {
            $product = new Product;
            $product->setName('Iphone '.$i);
            $product->setDescription($faker->paragraph());
            $product->setPrice(($i * 100) - 0.01);
            $manager->persist($product);
        }

        // Data Fixtures for Products, here Samsung
        for($i = 1; $i < 16; $i++) {
            $product = new Product;
            $product->setName('Samsung Galaxy S'.$i);
            $product->setDescription($faker->paragraph());
            $product->setPrice(($i * 100) - 0.01);
            $manager->persist($product);
        }

        // Data Fixtures for Clients
        for($i = 0; $i < 50; $i++) {
            $client = new Client();
            $client->setName($faker->firstName ." ". $faker->lastName);
            $client->setEmail($faker->email);
            $client->setUser($sfr);
            $manager->persist($client);
        }

        // Data Fixtures for Clients
        for($i = 0; $i < 50; $i++) {
            $client = new Client();
            $client->setName($faker->firstName ." ". $faker->lastName);
            $client->setEmail($faker->email);
            $client->setUser($orange);
            $manager->persist($client);
        }

        $manager->flush();
    }
}
