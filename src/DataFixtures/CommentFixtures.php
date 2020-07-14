<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Comment;
use Faker;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker =Faker\Factory::create('fr_FR');
        for ($i =0 ; $i <100; $i++){
            $comment = new Comment();
            $comment->setRate( random_int(1,5));
            $comment->setComment($faker->text(80));
            $comment->setAuthor(($this->getReference('subscriber_' . random_int(0,30))));
            $comment->setEpisode($this->getReference('episode_' . random_int(1,5)));

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [EpisodeFixtures::class,
            UserFixtures::class];
    }
}
