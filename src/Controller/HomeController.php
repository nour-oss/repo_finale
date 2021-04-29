<?php

namespace App\Controller;

use App\Entity\Examen;
use App\Entity\Formation;
use App\Services\QrcodeService;
use App\Entity\Reponse;
use App\Entity\Question;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $forms = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'forms'=>$forms
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
        if ($champ == "oui")
            $champ1 = "yes";
        elseif ($champ == "non")
            $champ1 = "no";
        $reponse = $this->getDoctrine()->getRepository(Reponse::class)->findBy(['vrai' => $champ1]);

        return $this->render("reponse/index.html.twig", [
            'reponses' => $reponse
        ]);


        return 0;
    }

    /**
     * @Route("/change_locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request)
    {
        // On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/qrcode/{note}", name="qrcode")
     * @param Request $request
     * @param QrcodeService $qrcodeService
     * @return Response
     */
    public function qrcode($note, QrcodeService $qrcodeService): Response
    {
        $qrCode = null;


        $data = $note;
        $qrCode = $qrcodeService->qrcode($data);


        return $this->render('examen/qrcode.html.twig', [
            
            'qrCode' => $qrCode
        ]);
    }

    /**
     * @Route("/stats" , name="stats")
     */
    public function stats(){
        $forms = $this->getDoctrine()->getRepository(Formation::class)->findAll();

        $formLabel=[];
        $formExams = [];

        foreach ($forms as $form){
            $formLabel[] = $form->getTitre();
            $formExams[] = count($this->getDoctrine()->getRepository(Examen::class)->findBy(['formationId'=>$form->getId()]));
        }

        return $this->render("examen/stat.html.twig",[
            'formLabel' => json_encode($formLabel),
            'formExams' => json_encode($formExams)
        ]);
    }
}
