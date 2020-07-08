<?php


namespace App\DataFixtures;


use App\Service\Slugify;
use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'Andrew Lincoln' => ['program_0', 'program_5'],
        'Norman Reedus' => ['program_0'],
        'Lauren Cohan' => ['program_0'],
        'Danai Gurira' => ['program_0'],
    ];

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

    function load(ObjectManager $manager)
    {
        $slug= new Slugify();

        $i = 0;
        foreach (self::ACTORS as $name => $data) {
            $actor = new Actor();
            $actor->setName($name);
            foreach ($data as $program) {
                $actor->addProgram($this->getReference($program));
                $actor->setSlug($slug->generate($name));
            }



            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
            $i++;
        }

        $faker= Faker\Factory::create('fr_FR');
        for ($i = 4; $i<=50;$i++){
            $actor = new Actor();
            $actor->setName($faker->name);
            $actor->setSlug($slug->generate($actor->getName()));


            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
            $actor->addProgram($this->getReference('program_'.random_int(0,29)));
        }
        $manager->flush();
    }


}

