<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private bool $isVerified = false;

    /**
     * @var Collection<int, UserSkillOffered>
     */
    #[ORM\OneToMany(targetEntity: UserSkillOffered::class, mappedBy: 'user')]
    private Collection $skillsOffered;

    /**
     * @var Collection<int, UserSkillWanted>
     */
    #[ORM\OneToMany(targetEntity: UserSkillWanted::class, mappedBy: 'user')]
    private Collection $userSkillWanteds;

    public function __construct()
    {
        $this->skillsOffered = new ArrayCollection();
        $this->userSkillWanteds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, UserSkillOffered>
     */
    public function getSkillsOffered(): Collection
    {
        return $this->skillsOffered;
    }

    public function addSkillsOffered(UserSkillOffered $skillsOffered): static
    {
        if (!$this->skillsOffered->contains($skillsOffered)) {
            $this->skillsOffered->add($skillsOffered);
            $skillsOffered->setUser($this);
        }

        return $this;
    }

    public function removeSkillsOffered(UserSkillOffered $skillsOffered): static
    {
        if ($this->skillsOffered->removeElement($skillsOffered)) {
            // set the owning side to null (unless already changed)
            if ($skillsOffered->getUser() === $this) {
                $skillsOffered->setUser(null);
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
            $userSkillWanted->setUser($this);
        }

        return $this;
    }

    public function removeUserSkillWanted(UserSkillWanted $userSkillWanted): static
    {
        if ($this->userSkillWanteds->removeElement($userSkillWanted)) {
            // set the owning side to null (unless already changed)
            if ($userSkillWanted->getUser() === $this) {
                $userSkillWanted->setUser(null);
            }
        }

        return $this;
    }
}
