<?php


namespace App\DataFixtures;

use App\Entity\Category;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES =[
        'Action',
        'Aventure',
        'Animation',
        'Fantastique',
        'Horreur',
    ];


    public function load(ObjectManager $manager)
    {
        $slug= new Slugify();

        foreach (self::CATEGORIES as $key => $categoryName){
            $category =new Category();
            $category ->setName($categoryName);
            $category->setSlug($slug->generate($categoryName));
            $manager->persist($category);
            $this->addReference('categorie_' . $key, $category);
        }
        $manager->flush();
    }
}
