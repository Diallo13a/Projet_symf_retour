<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class ProfilFixtures extends Fixture 
{
    public const ADMIN_REFERENCE = 'ADMIN';
    public const APPRENANT_REFERENCE = 'APPRENANT';
    public const FORMATEUR_REFERENCE = 'FORMATEUR';
    public const CM_REFERENCE = 'CM';
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        $profils_tab =["ADMIN","FORMATEUR","APPRENANT","CM"];

        for ($i=0; $i < count($profils_tab); $i++){
        $profil=new Profil();
        $profil->setLibelle($profils_tab[$i]);
            if($profils_tab[$i]=='ADMIN'){
                $this->addReference(self::ADMIN_REFERENCE,$profil);
            }elseif ($profils_tab[$i]=='APPRENANT'){
                $this->addReference(self::APPRENANT_REFERENCE,$profil);
            }elseif ($profils_tab[$i]=='FORMATEUR'){
                $this->addReference(self::FORMATEUR_REFERENCE,$profil);
            }elseif ($profils_tab[$i]=='CM'){
                $this->addReference(self::CM_REFERENCE,$profil);
            }

        $manager->persist($profil);
        }

        $manager->flush();
    }
}
