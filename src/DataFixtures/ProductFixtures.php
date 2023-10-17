<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Product;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
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

        $manager->flush();
    }
}
