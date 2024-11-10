<?php

// src/Entity/Team.php
namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
private $id;

#[ORM\Column(type: 'string', length: 255)]
private $teamName;

#[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'teams')]
private $user;

#[ORM\OneToMany(targetEntity: Player::class, mappedBy: 'team', cascade: ['persist', 'remove'])]
private $players;

public function __construct()
{
$this->players = new ArrayCollection();
}

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTeamName()
    {
        return $this->teamName;
    }

    /**
     * @param mixed $teamName
     */
    public function setTeamName($teamName): void
    {
        $this->teamName = $teamName;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function getPlayers(): ArrayCollection
    {
        return $this->players;
    }

    public function setPlayers(ArrayCollection $players): void
    {
        $this->players = $players;
    }

// Getters and setters


}
