<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\User;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Slugify;

/**
 * @Route("/program")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="program_index", methods={"GET"})
     * @param ProgramRepository $programRepository
     * @return Response
     */
    public function index(ProgramRepository $programRepository): Response
    {
        return $this->render('program/index.html.twig', [
            'programs' => $programRepository->findAllWithCategories()
        ]);
    }

    /**
     * @Route("/new", name="program_new", methods={"GET","POST"})
     * @param Request $request
     * @param Slugify $slugify
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $slug =$slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                ->from('n.gaget69@gmail.com')
                ->to('nicolas_gaget@yahoo.fr')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html('<p>Une nouvelle série vient d\'être publiée sur Wild Séries !</p>');

            $mailer->send($email);
            $this->addFlash('success', 'Un nouveau programme a bien été ajouté');

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="program_show", methods={"GET"})
     * @param Program $program
     * @param Slugify $slugify
     * @return Response
     */
    public function show(Program $program, Slugify $slugify): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="program_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Program $program
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Program $program): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="program_delete", methods={"DELETE"})
     * @param Request $request
     * @param Program $program
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Program $program): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
        }
        $this->addFlash('danger', 'Le programme a bien été supprimé');
        return $this->redirectToRoute('program_index');
    }

    /**
     * @param Program $program
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function addToWatchlist(Program $program, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        if($user->getWatchlist()->contains($program)) {
            $user->removeWatchlist($program);
        }else {
            $user->addWatchlist($program);
        }
        $manager->flush();

        //return $this->redirectToRoute('program_show', ["slug" => $program->getSlug()]);
        return $this->json([
            'isInWatchlist' => $this->getUser()->isInWatchlist($program)
        ]);

    }

}
