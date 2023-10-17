<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public const SFR_USER_REFERENCE = "user-sfr";
    public const ORANGE_USER_REFERENCE = "user-orange";
    public const BOUYGUES_USER_REFERENCE = "user-bouygues";
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
        $orange->setEmail('orange@telecom.fr');
        $orange->setName('Orange Telecom');
        $orange->setRoles(["ROLE_USER"]);
        $orange->setPassword($this->userPasswordHasher->hashPassword($orange, "passwordOrange"));
        $manager->persist($orange);
        $this->addReference(self::ORANGE_USER_REFERENCE, $orange);

        // Data Fixtures for User 
        $sfr = new User();
        $sfr->setEmail('sfr@telecom.fr');
        $sfr->setName('SFR Telecom');
        $sfr->setRoles(["ROLE_USER"]);
        $sfr->setPassword($this->userPasswordHasher->hashPassword($sfr, "passwordSFR"));
        $manager->persist($sfr);
        $this->addReference(self::SFR_USER_REFERENCE, $sfr);

        // Data Fixtures for User 
        $bouygues = new User();
        $bouygues->setEmail('bouygues@telecom.fr');
        $bouygues->setName('Bouygues Telecom');
        $bouygues->setRoles(["ROLE_USER"]);
        $bouygues->setPassword($this->userPasswordHasher->hashPassword($bouygues, "passwordBouygues"));
        $manager->persist($bouygues);
        $this->addReference(self::BOUYGUES_USER_REFERENCE, $bouygues);

        $manager->flush();
    }
}
