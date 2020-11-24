<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Apprenant;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenatFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        $user = new Apprenant();
        $user->setUsername($faker->name);

        $password = $this->encoder->encodePassword($user, 'pass_1234');
        $user->setPassword($password);
        $user->setProfil($this->getReference(ProfilFixtures::APPRENANT_REFERENCE));

        $manager->persist($user);
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
