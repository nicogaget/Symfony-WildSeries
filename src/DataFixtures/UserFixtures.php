<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture
{
    private  $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    public function load(ObjectManager $manager)
    {
        $faker =Faker\Factory::create('fr_FR');

        for ($i=0 ; $i<100; $i++) {

            $subscriber = new User();
            $subscriber->setEmail('user' .$i. '@mail.com');
            $subscriber->setNickname(strtoupper($faker->text(10)));
            $subscriber->setRoles(['ROLE_SUBSCRIBER']);
            $subscriber->setPassword($this->passwordEncoder->encodePassword(
                $subscriber,
                'userpass'
            ));
            $this->addReference('subscriber_' . $i,$subscriber);

            $manager->persist($subscriber);
        }

        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setEmail('admin@mail.com');
        $admin->setNickname('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'adminpass'
        ));

        $manager->persist($admin);



        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();

    }
}
