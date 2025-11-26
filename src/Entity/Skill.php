<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    /**
     * @var Collection<int, UserSkillOffered>
     */
    #[ORM\OneToMany(targetEntity: UserSkillOffered::class, mappedBy: 'skill')]
    private Collection $userSkillOffereds;

    /**
     * @var Collection<int, UserSkillWanted>
     */
    #[ORM\OneToMany(targetEntity: UserSkillWanted::class, mappedBy: 'skill')]
    private Collection $userSkillWanteds;

    public function __construct()
    {
        $this->userSkillOffereds = new ArrayCollection();
        $this->userSkillWanteds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, UserSkillOffered>
     */
    public function getUserSkillOffereds(): Collection
    {
        return $this->userSkillOffereds;
    }

    public function addUserSkillOffered(UserSkillOffered $userSkillOffered): static
    {
        if (!$this->userSkillOffereds->contains($userSkillOffered)) {
            $this->userSkillOffereds->add($userSkillOffered);
            $userSkillOffered->setSkill($this);
        }

        return $this;
    }

    public function removeUserSkillOffered(UserSkillOffered $userSkillOffered): static
    {
        if ($this->userSkillOffereds->removeElement($userSkillOffered)) {
            // set the owning side to null (unless already changed)
            if ($userSkillOffered->getSkill() === $this) {
                $userSkillOffered->setSkill(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserSkillWanted>
     */
    public function getUserSkillWanteds(): Collection
    {
        return $this->userSkillWanteds;
    }

    public function addUserSkillWanted(UserSkillWanted $userSkillWanted): static
    {
        if (!$this->userSkillWanteds->contains($userSkillWanted)) {
            $this->userSkillWanteds->add($userSkillWanted);
            $userSkillWanted->setSkill($this);
        }

        return $this;
    }

    public function removeUserSkillWanted(UserSkillWanted $userSkillWanted): static
    {
        if ($this->userSkillWanteds->removeElement($userSkillWanted)) {
            // set the owning side to null (unless already changed)
            if ($userSkillWanted->getSkill() === $this) {
                $userSkillWanted->setSkill(null);
            }
        }

        return $this;
    }
}
