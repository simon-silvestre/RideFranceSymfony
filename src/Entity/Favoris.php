<?php

namespace App\Entity;

use App\Repository\FavorisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FavorisRepository::class)
 */
class Favoris
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=SkateParks::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $skatepark;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="favoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $username;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkatepark(): ?SkateParks
    {
        return $this->skatepark;
    }

    public function setSkatepark(SkateParks $skatepark): self
    {
        $this->skatepark = $skatepark;

        return $this;
    }

    public function getUsername(): ?Users
    {
        return $this->username;
    }

    public function setUsername(?Users $username): self
    {
        $this->username = $username;

        return $this;
    }
}
