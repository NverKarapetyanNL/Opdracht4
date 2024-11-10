<?php
// src/Controller/PlayerController.php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    #[Route('/player/new', name: 'app_player')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $player = new Player();

        $teams = $entityManager->getRepository(Team::class)->findAll();
        $players = $entityManager->getRepository(Player::class)->findAll(); // Haal alle spelers op

        if ($request->isMethod('POST')) {
            $playerName = $request->request->get('playerName');
            $teamId = $request->request->get('team_id');

            if ($playerName && $teamId) {
                $player->setPlayerName($playerName);

                $team = $entityManager->getRepository(Team::class)->find($teamId);
                if ($team) {
                    $player->setTeam($team);
                    $entityManager->persist($player);
                    $entityManager->flush();

                    $this->addFlash('success', 'Player successfully created and added to the team!');
                    return $this->redirectToRoute('app_player');
                } else {
                    $this->addFlash('error', 'Selected team not found.');
                }
            } else {
                $this->addFlash('error', 'Player name and team are required.');
            }
        }

        return $this->render('player/index.html.twig', [
            'teams' => $teams,
            'players' => $players, // Voeg spelers toe aan de template-data
        ]);
    }
}
