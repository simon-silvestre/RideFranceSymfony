<?php

namespace App\Entity;

use App\Entity\SkateParks;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FavorisRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="favoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=SkateParks::class, mappedBy="favoris")
     */
    private $skatepark;

    public function __construct()
    {
        $this->skatepark = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|SkateParks[]
     */
    public function getSkatepark(): Collection
    {
        return $this->skatepark;
    }

    public function addSkatepark(SkateParks $skatepark): self
    {
        if (!$this->skatepark->contains($skatepark)) {
            $this->skatepark[] = $skatepark;
            $skatepark->setFavoris($this);
        }

        return $this;
    }

    public function removeSkatepark(SkateParks $skatepark): self
    {
        if ($this->skatepark->contains($skatepark)) {
            $this->skatepark->removeElement($skatepark);
            // set the owning side to null (unless already changed)
            if ($skatepark->getFavoris() === $this) {
                $skatepark->setFavoris(null);
            }
        }

        return $this;
    }
}
