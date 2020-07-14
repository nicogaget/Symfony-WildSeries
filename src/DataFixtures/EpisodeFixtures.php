<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

use App\Service\Slugify;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }


    public function load(ObjectManager $manager)
    {
        $faker =Faker\Factory::create('fr_FR');

        $slug = new Slugify();
        for ($i=0; $i<40 ; $i++)
        {
            $episode = new Episode();
            $episode->setTitle($faker->text(50));
            $episode->setNumber($faker->randomDigit);
            $episode->setSynopsis($faker->text);
            $episode->setSlug($slug->generate($episode->getTitle()));

            $episode->setSeason($this->getReference('season_' . random_int(1,14)));
            $this->addReference('episode_' . $i, $episode);
            $manager->persist($episode);

        }
        $manager->flush();
    }
}
