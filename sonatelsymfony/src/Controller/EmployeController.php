<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Employe;
use App\Entity\Service;
use App\Entity\Repository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EmployeController extends AbstractController
{
    /**
     * @Route("/employe", name="employe")
     */
    public function index()
    {
        return $this->render('employe/index.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }


    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('employe/home.html.twig');
    }


    /**
     * @Route("employe/new", name="sonatel_create")
     * @Route("employe/{id}/edit", name="sonatel_edit")
     * @Route("employe/liste/{id}", name="liste_edit")
     */
    public function form(Employe $employe=null, Request $request, ObjectManager $manager)
    {
        if(!$employe){
            $employe = new Employe();
        }
        $form = $this->createFormBuilder($employe)
            ->add('matricule')
            ->add('nomcomplet')
            ->add('datenaiss', DateType::class, [
                'widget' => "single_text",
                'format' => "yyyy-MM-dd"
            ])
            ->add('salaire')
            // ->add('id',EntityType::class,[ 
            //     'class' => Service::class,
            //     'choice_label' => 'libelle',
            // ])
            ->getForm();

        $form->handlerequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($employe);
            $manager->flush();

            return $this->redirectToRoute('sonatel_liste', [
                'id' => $employe->getId()
            ]);
        }

        return $this->render('employe/create.html.twig', [
            'formEmploye' => $form->createView(),
            'editMode' => $employe->getId() !== null,
            'supMode' => $employe->getId() !== null
        ]);
    }


    /**
     * @Route("employe/liste", name="sonatel_liste")
     */

    public function liste()
    {
        $repo = $this->getDoctrine()->getRepository(Employe::class);

        $employes = $repo->findAll();    

        return $this->render('employe/liste.html.twig', [
            'employes' => $employes
        ] );
    }



}
