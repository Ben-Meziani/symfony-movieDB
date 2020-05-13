<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Person {

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
    *
    * @ORM\OneToMany(targetEntity="App\Entity\Movie", mappedBy="director")
    */
  private $movies;

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
  
}