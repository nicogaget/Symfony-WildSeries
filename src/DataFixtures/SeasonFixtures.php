<?php


namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return[ProgramFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker= Faker\Factory::create('fr_FR');

        for( $i=0; $i<100;$i++){
            $season = new Season();
            $season->setNumber(rand(1,5));
            $season->setYear($faker->year);
            $season->setDescription($faker->text);
            $season->setProgram($this->getReference('program_'.random_int(0,5)));
            $this->addReference('season_' . $i, $season);

            $manager->persist($season);
        }
        $manager->flush();
    }
}
