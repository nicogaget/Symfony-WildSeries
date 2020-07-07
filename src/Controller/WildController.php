<?php


namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Repository\ActorRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Service\Slugify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WildController
 * @package App\Controller
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param ProgramRepository $programRepo
     * @param Request $request
     * @return Response
     */
    public function index(ActorRepository $actorRepo, ProgramRepository $programRepo, Request $request) : Response
    {
        $programs =$programRepo->findAll();

        $actors = $actorRepo->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }


        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
            'programs' => $programs,
            'actors' => $actors

        ]);
    }

    /**
     * @param ProgramRepository $programRepo
     * @param string $slug The slugger
     * @return Response
     * @Route("/show/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = "Aucune série sélectionnée, veuillez choisir une série"},
     *     name="show")
     */
    public function show(ProgramRepository $programRepo, string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $programRepo->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }
        $actors = $program->getActors();
        $seasons = $program->getSeasons();


        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
            'actors' => $actors,
            'seasons' =>$seasons
        ]);
    }

    /**
     * @param string $categoryName
     * @return Response
     * @Route("/category/{categoryName}", name="category")
     */
    public function showByCategory(string $categoryName)
    {

        $category = $this->getDoctrine()->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        $programs = $this->getDoctrine()->getRepository(Program::class)
            ->findBy(array('category' => $category),
            array('id' => 'desc'),
            3);
        return $this->render('wild/category.html.twig',[
            'programs' => $programs,
            'category' => $category
        ]);

    }

    /**
     * @Route("/season/{id<^[0-9]+$>}", defaults={"id" = null}, name="season")
     * @param SeasonRepository $seasonRepo
     * @param int $id
     * @return Response
     */
    public function showBySeason(SeasonRepository $seasonRepo, int $id): Response
    {
        if (!$id){
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $season = $seasonRepo->findOneBy(['id'=>$id]);

        $program =$season->getProgram();

        $episodes =$season->getEpisodes();

        return $this->render('wild/season.html.twig',[
            'season'=>$season,
            'program'=>$program,
            'episodes'=>$episodes,
        ]);
    }

    /**
     * @param Episode $episode
     * @return Response
     * @Route("/episode/{id<^[0-9]+$>}", name="episode")
     */
    public function showEpisode(Episode $episode)
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        if (!$episode) {
            throw $this->createNotFoundException(
                'No episode found in episode\'s table.'
            );
        }
        return $this->render('episode.html;twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program
        ]);
    }
    /**
     * @param int $id
     * @return Response
     * @Route("/actor/{id<^[0-9]+$>}", name="actor")
     */
    public function showActor(int $id):Response
    {
        $actor= $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findOneBy(['id'=>$id]);

        $programs=$actor->getPrograms();

        return $this->render('wild/actor.html.twig',[
            'actor'=>$actor,
            'programs'=>$programs
        ]);
    }
}
