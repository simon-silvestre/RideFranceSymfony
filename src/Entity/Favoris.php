<?php

namespace App\Entity;

use App\Repository\FavorisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=FavorisRepository::class)
 * @UniqueEntity(
 *  fields= {"skatepark", "username"},
 *  message= "Le skatepark est deja dans vos favoris !"
 * )
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
     * @ORM\ManyToOne(targetEntity=SkateParks::class, inversedBy="favoris")
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
