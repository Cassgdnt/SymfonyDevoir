<?php

namespace App\Controller;

use App\Entity\Accueil;
use App\Form\AccueilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request)
    {
        //connexion à la bdd
        $pdo = $this->getDoctrine()->getManager();

        //envoi dans la bdd
        $accueil =new Accueil();
        $accueil->setDate(new \DateTime('now'));
        $form = $this->createForm(AccueilType::class, $accueil);
        //analyse la requette http
        $form->handleRequest($request);
        if($form-> isSubmitted() && $form->isValid()){
            $pdo->persist($accueil);
            $pdo->flush(); 
        }

        $accueil =$pdo->getRepository(Accueil::class)->findAll();
        
        return $this->render('accueil/index.html.twig', [
            'accueils' => $accueil,
            'form_ajout' => $form->createView()
        ]);
    }
    /**
    *@Route("accueil\{id}", name="edit_accueil")
    */
    public function accueil(Accueil $accueil=null, Request $request){
        if($accueil != null){
        $form = $this->createForm(AccueilType::class, $accueil);

        $form->handleRequest($request);
        if($form-> isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($accueil); 
            $pdo->flush(); 
        }

        return $this->render('accueil/accueil.html.twig', [
            'accueil' => $accueil,
            'form_edit'=>$form->createView()
        ]);
        }
    }
}
