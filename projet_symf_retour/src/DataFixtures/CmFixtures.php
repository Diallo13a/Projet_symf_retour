<?php

namespace App\DataFixtures;

use App\Entity\Cm;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CmFixtures extends Fixture
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
        $user = new Cm();
        $user->setUsername($faker->name);

        $password = $this->encoder->encodePassword($user, 'pass_1234');
        $user->setPassword($password);
        $user->setProfil($this->getReference(ProfilFixtures::CM_REFERENCE));

        $manager->persist($user);
        $manager->flush();
    }
}
