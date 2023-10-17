<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Client;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
       
        // Data Fixtures for Clients
        for($i = 0; $i < 50; $i++) {
            $client = new Client();
            $client->setFirstname($faker->firstName);
            $client->setLastname($faker->lastName);
            $client->setEmail($faker->email);
            $client->setPhone($faker->e164PhoneNumber);
            $client->setUser($this->getReference(UserFixtures::SFR_USER_REFERENCE));
            $manager->persist($client);
        }

        // Data Fixtures for Clients
        for($i = 0; $i < 50; $i++) {
            $client = new Client();
            $client->setFirstname($faker->firstName);
            $client->setLastname($faker->lastName);
            $client->setEmail($faker->email);
            $client->setPhone($faker->e164PhoneNumber);
            $client->setUser($this->getReference(UserFixtures::ORANGE_USER_REFERENCE));
            $manager->persist($client);
        }

        // Data Fixtures for Clients
        for($i = 0; $i < 50; $i++) {
            $client = new Client();
            $client->setFirstname($faker->firstName);
            $client->setLastname($faker->lastName);
            $client->setEmail($faker->email);
            $client->setPhone($faker->e164PhoneNumber);
            $client->setUser($this->getReference(UserFixtures::BOUYGUES_USER_REFERENCE));
            $manager->persist($client);
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
