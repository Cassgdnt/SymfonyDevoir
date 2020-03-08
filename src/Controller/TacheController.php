<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Form\TacheType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TacheController extends AbstractController
{
    /**
     * @Route("/tache", name="tache")
     */
    public function index(Request $request)
    {
        //connexion à la bdd
        $pdo = $this->getDoctrine()->getManager();
        $tache =new Tache();
        $form = $this->createForm(TacheType::class, $tache);
        //analyse la requette http
        $form->handleRequest($request);
        if($form-> isSubmitted() && $form->isValid()){
            $pdo->persist($tache); //prepare
            $pdo->flush(); //execute
        }
        $tache =$pdo->getRepository(Tache::class)->findAll();

        return $this->render('tache/index.html.twig', [
            'taches' => $tache,
            'form_ajout' => $form->createView()
        ]);
    }
    /**
    *@Route("/tache/{id}", name="edit_tache")
    */
    public function tache(Tache $tache=null, Request $request){
        if($tache != null){
        $form = $this->createForm(TacheType::class, $tache);

        //analyse la requette http
        $form->handleRequest($request);
        if($form-> isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($tache); 
            $pdo->flush();
        }

        return $this->render('tache/tache.html.twig', [
            'tache' => $tache,
            'form_edit'=>$form->createView()
        ]);
        }
    }
    /**
     *@Route("/tache/delete/{id}", name="delete_tache")  
     */
    public function delete(Tache $tache=null){
        //si il y a, on supprime
        if($tache !=null){
            $pdo=$this->getDoctrine()->getManager();
            $pdo->remove($tache);
            $pdo->flush();
        }
        //sinon on affiche une autre page
        return $this->redirectToRoute('tache');
    }
}
