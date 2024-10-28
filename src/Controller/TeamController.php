<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TeamController extends AbstractController
{
    /**
     * @Route("/team/new", name="team_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $team = new Team();
        $form = $this->createFormBuilder($team)
            ->add('team_name', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Team'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team->setUser($this->getUser());  // Koppel het team aan de ingelogde gebruiker
            $entityManager->persist($team);
            $entityManager->flush();

            return $this->redirectToRoute('team_list');
        }

        return $this->render('team/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Implementeer hier de overige CRUD-operaties (bijv. read, update, delete)
}

