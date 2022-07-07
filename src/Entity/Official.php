<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\OfficialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ApiResource]
#[ORM\Entity(repositoryClass: OfficialRepository::class)]
class Official
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    private $firstName;
    #[ORM\Column(type: 'string', length: 32)]
    private $lastName;
    #[ORM\Column(type: 'string', length: 48)]
    private $officialName;
    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private $birthday;
    #[ORM\Column(type: 'string', length: 1, nullable: true)]
    private $gender;
    #[ORM\OneToMany(mappedBy: 'offical', targetEntity: Term::class, orphanRemoval: true)]
    private $terms;
    public function __construct()
    {
        $this->terms = new ArrayCollection();
    }
    public function getId() : ?int
    {
        return $this->id;
    }
    public function getFirstName() : ?string
    {
        return $this->firstName;
    }
    public function setFirstName(?string $firstName) : self
    {
        $this->firstName = $firstName;
        return $this;
    }
    public function getLastName() : ?string
    {
        return $this->lastName;
    }
    public function setLastName(string $lastName) : self
    {
        $this->lastName = $lastName;
        return $this;
    }
    public function getOfficialName() : ?string
    {
        return $this->officialName;
    }
    public function setOfficialName(string $officialName) : self
    {
        $this->officialName = $officialName;
        return $this;
    }
    public function getBirthday() : ?\DateTimeImmutable
    {
        return $this->birthday;
    }
    public function setBirthday(?\DateTimeImmutable $birthday) : self
    {
        $this->birthday = $birthday;
        return $this;
    }
    public function getGender() : ?string
    {
        return $this->gender;
    }
    public function setGender(?string $gender) : self
    {
        $this->gender = $gender;
        return $this;
    }
    /**
     * @return Collection<int, Term>
     */
    public function getTerms() : Collection
    {
        return $this->terms;
    }
    public function addTerm(Term $term) : self
    {
        if (!$this->terms->contains($term)) {
            $this->terms[] = $term;
            $term->setOffical($this);
        }
        return $this;
    }
    public function removeTerm(Term $term) : self
    {
        if ($this->terms->removeElement($term)) {
            // set the owning side to null (unless already changed)
            if ($term->getOffical() === $this) {
                $term->setOffical(null);
            }
        }
        return $this;
    }
}
