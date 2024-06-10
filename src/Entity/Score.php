<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_user = null;

    #[ORM\Column]
    private ?int $id_game = null;

    #[ORM\Column]
    private ?int $nb_Points = null;

    #[ORM\Column(nullable: true)]
    private ?int $start_Game = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_Game = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdGame(): ?int
    {
        return $this->id_game;
    }

    public function setIdGame(int $id_game): static
    {
        $this->id_game = $id_game;

        return $this;
    }

    public function getNbPoints(): ?int
    {
        return $this->nb_Points;
    }

    public function setNbPoints(int $nb_Points): static
    {
        $this->nb_Points = $nb_Points;

        return $this;
    }

    public function getStartGame(): ?int
    {
        return $this->start_Game;
    }

    public function setStartGame(?int $start_Game): static
    {
        $this->start_Game = $start_Game;

        return $this;
    }

    public function getEndGame(): ?\DateTimeInterface
    {
        return $this->end_Game;
    }

    public function setEndGame(?\DateTimeInterface $end_Game): static
    {
        $this->end_Game = $end_Game;

        return $this;
    }
}
