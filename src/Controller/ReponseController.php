<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Question;
use App\Entity\Formation;
use App\Form\ReponseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/reponse")
 */
class ReponseController extends AbstractController
{
    /**
     * @Route("/", name="reponse_index", methods={"GET"})
     */
    public function index(): Response
    {
        $reponses = $this->getDoctrine()
            ->getRepository(Reponse::class)
            ->findAll();

        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponses,
        ]);
    }

    /**
     * @Route("/new", name="reponse_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        $formations = $this->getDoctrine()->getRepository(Question::class)->findAll();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $this->getDoctrine()->getRepository(Question::class)->findOneBy(['question' => $request->request->get('formation')]);
            $reponse->setIdquestion($id->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('reponse_index');
        }

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
            'formations' => $formations
        ]);
    }

    /**
     * @Route("/{id}", name="reponse_show", methods={"GET"})
     */
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reponse_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reponse $reponse): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        $formations = $this->getDoctrine()->getRepository(Question::class)->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reponse_index');
        }

        return $this->render('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
            'formations' => $formations 
        ]);
    }

    /**
     * @Route("/{id}", name="reponse_delete", methods={"POST"})
     */
    public function delete(Request $request, Reponse $reponse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reponse_index');
    }
}
