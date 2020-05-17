<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonRepository;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie", mappedBy="writers")
     */
    private $writedMovies;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Movie", mappedBy="director")
     */
    private $directedMovies;

    /**
     * @ORM\OneToMany(targetEntity=MovieActor::class, mappedBy="person", orphanRemoval=true)
     */
    private $movieActors;

    public function __construct()
    {
        $this->writedMovies = new ArrayCollection();
        $this->directedMovies = new ArrayCollection();
        $this->movieActors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }


    /**
     * @return Collection|Movie[]
     */
    public function getWritedMovies(): Collection
    {
        return $this->writedMovies;
    }

    public function addWritedMovie(Movie $writedMovie): self
    {
        if (!$this->writedMovies->contains($writedMovie)) {
            $this->writedMovies[] = $writedMovie;
            $writedMovie->addWriter($this);
        }

        return $this;
    }

    public function removeWritedMovie(Movie $writedMovie): self
    {
        if ($this->writedMovies->contains($writedMovie)) {
            $this->writedMovies->removeElement($writedMovie);
            $writedMovie->removeWriter($this);
        }

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getDirectedMovies(): Collection
    {
        return $this->directedMovies;
    }

    public function addDirectedMovie(Movie $directedMovie): self
    {
        if (!$this->directedMovies->contains($directedMovie)) {
            $this->directedMovies[] = $directedMovie;
            $directedMovie->setDirector($this);
        }

        return $this;
    }

    public function removeDirectedMovie(Movie $directedMovie): self
    {
        if ($this->directedMovies->contains($directedMovie)) {
            $this->directedMovies->removeElement($directedMovie);
            // set the owning side to null (unless already changed)
            if ($directedMovie->getDirector() === $this) {
                $directedMovie->setDirector(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MovieActor[]
     */
    public function getMovieActors(): Collection
    {
        return $this->movieActors;
    }

    public function addMovieActor(MovieActor $movieActor): self
    {
        if (!$this->movieActors->contains($movieActor)) {
            $this->movieActors[] = $movieActor;
            $movieActor->setPerson($this);
        }

        return $this;
    }

    public function removeMovieActor(MovieActor $movieActor): self
    {
        if ($this->movieActors->contains($movieActor)) {
            $this->movieActors->removeElement($movieActor);
            // set the owning side to null (unless already changed)
            if ($movieActor->getPerson() === $this) {
                $movieActor->setPerson(null);
            }
        }

        return $this;
    }
}
