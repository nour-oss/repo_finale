<?php

namespace App\Controller;

use App\Entity\Examen;
use App\Entity\Reponse;
use App\Entity\Question;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/admin", name="home_back")
     */
    public function index1(): Response
    {
        return $this->render('home/index1.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/rech" , name="rech")
     */
    public function rech(Request $request)
    {
        $champ = $request->request->get('rech');
        $examen = $this->getDoctrine()->getRepository(Examen::class)->findOneBy(['id' => $champ]);
        $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(['id' => $champ]);
        $reponse = $this->getDoctrine()->getRepository(Reponse::class)->findOneBy(['id' => $champ]);

        if ($examen != NULL)
            return $this->render("examen/show.html.twig", [
                'examan' => $examen
            ]);
        elseif ($question != NULL)
            return $this->render("question/show.html.twig", [
                'question' => $question
            ]);
        elseif ($reponse != NULL)
            return $this->render("reponse/show.html.twig", [
                'reponse' => $reponse
            ]);

        $examen = $this->getDoctrine()->getRepository(Examen::class)->findOneBy(['description' => $champ]);
        $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(['question' => $champ]);
        $reponse = $this->getDoctrine()->getRepository(Reponse::class)->findOneBy(['reponse' => $champ]);

        if ($examen != NULL)
            return $this->render("examen/show.html.twig", [
                'examan' => $examen
            ]);
        elseif ($question != NULL)
            return $this->render("question/show.html.twig", [
                'question' => $question
            ]);
        elseif ($reponse != NULL)
            return $this->render("reponse/show.html.twig", [
                'reponse' => $reponse
            ]);
            if($champ == "oui")
                $champ1 = "yes";
            elseif($champ == "non")
                $champ1 = "no";
            $reponse = $this->getDoctrine()->getRepository(Reponse::class)->findBy(['vrai' => $champ1]);
           
            return $this->render("reponse/index.html.twig", [
                'reponses' => $reponse
            ]);
            

        return 0;
    }
}
