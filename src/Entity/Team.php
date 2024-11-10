<?php

// src/Entity/Team.php
namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private mixed $id;

    #[ORM\Column(type: 'string', length: 255)]
    private mixed $teamName;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'teams')]
    private mixed $user;

    #[ORM\OneToMany(targetEntity: Player::class, mappedBy: 'team', cascade: ['persist', 'remove'])]
    private Collection $players;

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTeamName(): ?string
    {
        return $this->teamName;
    }

    public function setTeamName(string $teamName): self
    {
        $this->teamName = $teamName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser(): mixed
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser(mixed $user): void
    {
        $this->user = $user;
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function setPlayers(ArrayCollection $players): void
    {
        $this->players = $players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // Stel de relatie in op null als het element uit de collectie is verwijderd
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }


}
