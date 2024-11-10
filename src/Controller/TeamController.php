<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Zorg ervoor dat je een formulier hebt waarin spelers worden toegevoegd
// Formulier voor spelers

class TeamController extends AbstractController
{
    #[Route('/team/new', name: 'team_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createFormBuilder($team)
            ->add('teamName', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Team'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($team->getPlayers() as $player) {
                $player->setTeam($team);
            }

            $team->setUser($this->getUser());
            $entityManager->persist($team);
            $entityManager->flush();

            return $this->redirectToRoute('team_new');

        }
        $teams = $entityManager->getRepository(Team::class)->findAll();

        return $this->render('team/new.html.twig', [
            'form' => $form->createView(),
            'teams' => $teams,
        ]);
    }

    #[Route('/team', name: 'team_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $teams = $entityManager->getRepository(Team::class)->findAll();

        return $this->render('team/list.html.twig', [
            'teams' => $teams,
        ]);
    }

    #[Route('/team/edit/{id}', name: 'team_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = $entityManager->getRepository(Team::class)->find($id);

        if (!$team) {
            throw $this->createNotFoundException('No team found for id ' . $id);
        }

        $form = $this->createFormBuilder($team)
            ->add('teamName', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Update Team'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($team->getPlayers() as $player) {
                $player->setTeam($team); // Zorg dat de relatie met het team intact blijft
            }

            $entityManager->flush();

            return $this->redirectToRoute('team_list');
        }

        return $this->render('team/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/team/delete/{id}', name: 'team_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $team = $entityManager->getRepository(Team::class)->find($id);

        if (!$team) {
            throw $this->createNotFoundException('No team found for id ' . $id);
        }

        $entityManager->remove($team);
        $entityManager->flush();
        $this->addFlash('success', 'Team successfully deleted.');

        return $this->redirectToRoute('team_list');
    }
}
