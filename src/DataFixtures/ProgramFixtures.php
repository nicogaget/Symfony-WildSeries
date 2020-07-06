<?php


namespace App\DataFixtures;

use App\Service\Slugify;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        'Walking Dead' => [
            'summary' => 'Le policier Rick Grimes se réveille après un long coma.
             Il découvre avec effarement que le monde, ravagé par une épidémie, est envahi par les morts-vivants.',

            'poster' => 'https://upload.wikimedia.org/wikipedia/en/1/1c/Walking_Dead_S8_Poster.jpg',

            'category' => 'categorie_4',
            'country' => 'États-Unis',
            'year' => 2010,

        ],

        'The Haunting Of Hill House' => [
            'summary' => 'Plusieurs frères et sœurs qui, enfants,
             ont grandi dans la demeure qui allait devenir la maison hantée la plus célèbre des États-Unis,
             sont contraints de se réunir pour finalement affronter les fantômes de leur passé.',

            'poster' => 'https://fr.web.img2.acsta.net/pictures/18/09/19/18/46/2766026.jpg',

            'category' => 'categorie_4',
            'country' => 'États-Unis',
            'year' => 2018,
        ],

        'American Horror Story' => [
            'summary' => 'A chaque saison, son histoire.
             American Horror Story nous embarque dans des récits à la fois poignants et cauchemardesques,
             mêlant la peur, le gore et le politiquement correct.',

            'poster' => 'https://fr.web.img3.acsta.net/pictures/19/08/14/10/00/0580682.jpg',

            'category' => 'categorie_4',
            'country' => 'États-Unis',
            'year' => 2011,
        ],

        'Love Death And Robots' => [
            'summary' => 'Un yaourt susceptible, des soldats lycanthropes,
             des robots déchaînés, des monstres-poubelles, des chasseurs de primes cyborgs,
             des araignées extraterrestres et des démons assoiffés de sang :
             tout ce beau monde est réuni dans 18 courts métrages animés déconseillés aux âmes sensibles.',

            'poster' => 'https://fr.web.img2.acsta.net/pictures/19/02/15/09/58/1377321.jpg',

            'category' => 'categorie_4',
            'country' => 'États-Unis',
            'year' => 2019,
        ],

        'Penny Dreadful' => [
            'summary' => 'Dans le Londres ancien, Vanessa Ives,
             une jeune femme puissante aux pouvoirs hypnotiques,
              allie ses forces à celles de Ethan,
              un garçon rebelle et violent aux allures de cowboy, et de Sir Malcolm,
              un vieil homme riche aux ressources inépuisables. Ensemble, ils combattent un ennemi inconnu,
              presque invisible, qui ne semble pas humain et qui massacre la population.',

            'poster' => 'https://www.cinechronicle.com/wp-content/uploads/2014/08/Penny-Dreadful-poster-non-officiel.jpg',

            'category' => 'categorie_4',
            'country' => 'États-Unis / Gande-Bretagne',
            'year' => 2016,
        ],

        'Fear The Walking Dead' => [
            'summary' => 'La série se déroule au tout début de l épidémie relatée dans la série mère The Walking Dead,
             et se passe dans la ville de Los Angeles, et non à Atlanta.
             Madison est conseillère dans un lycée de Los Angeles. 
             Depuis la mort de son mari, elle élève seule ses deux enfants : Alicia,
             excellente élève qui découvre les premiers émois amoureux,
             et son grand frère Nick qui a quitté la fac et a sombré dans la drogue.',

            'poster' => 'https://m.media-amazon.com/images/M/MV5BYWNmY2Y1NTgtYTExMS00NGUxLWIxYWQtMjU4MjNkZjZlZjQ3XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_.jpg',

            'category' => 'categorie_4',
            'country' => 'États-Unis',
            'year' => 2015,
        ],
    ];
    public function getDependencies()
    {
        return[CategoryFixtures::class];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $slug= new Slugify();

        $i = 0;
        foreach (self::PROGRAMS as $title => $data) {
            $program = new Program();
            $program->setTitle($title);
            $program->setsummary($data['summary']);
            $program->setPoster($data['poster']);
            $program->setCountry($data['country']);
            $program->setYear($data['year']);

            $program->setSlug($slug->generate($title));


            $this->addReference('program_' . $i, $program);
            $program->setCategory($this->getReference($data['category']));

            $manager->persist($program);
            $i++;
        }
        $manager->flush();
    }


}
