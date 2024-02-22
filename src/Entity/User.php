<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déja avec cet e-mail')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstNameUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastNameUser = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRegisterUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pictureUser = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $roleUser = null;

    #[ORM\ManyToMany(targetEntity: Course::class, inversedBy: 'participateUsers')]
    private Collection $participateCourses;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Note::class)]
    private Collection $notes;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Course::class, cascade: ["remove"],
        orphanRemoval: true)]
    private Collection $courses;

    public function __construct()
    {
        $this->participateCourses = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->courses = new ArrayCollection();
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstNameUser(): ?string
    {
        return $this->firstNameUser;
    }

    public function setFirstNameUser(?string $firstNameUser): static
    {
        $this->firstNameUser = $firstNameUser;

        return $this;
    }

    public function getLastNameUser(): ?string
    {
        return $this->lastNameUser;
    }

    public function setLastNameUser(?string $lastNameUser): static
    {
        $this->lastNameUser = $lastNameUser;

        return $this;
    }

    public function getDateRegisterUser(): ?\DateTimeInterface
    {
        return $this->dateRegisterUser;
    }

    public function setDateRegisterUser(\DateTimeInterface $dateRegisterUser): static
    {
        $this->dateRegisterUser = $dateRegisterUser;

        return $this;
    }

    public function getPictureUser(): ?string
    {
        return $this->pictureUser;
    }

    public function setPictureUser(?string $pictureUser): static
    {
        $this->pictureUser = $pictureUser;

        return $this;
    }

    public function getRoleUser(): ?Role
    {
        return $this->roleUser;
    }

    public function setRoleUser(?Role $role): static
    {
        $this->roleUser = $role;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getParticipateCourses(): Collection
    {
        return $this->participateCourses;
    }

    public function addParticipateCourse(Course $Course): static
    {
        if (!$this->participateCourses->contains($Course)) {
            $this->participateCourses->add($Course);
        }

        return $this;
    }

    public function removeParticipateCourse(Course $Course): static
    {
        $this->participateCourses->removeElement($Course);

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setUsers($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getUsers() === $this) {
                $note->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getCreatedBy() === $this) {
                $course->setCreatedBy(null);
            }
        }

        return $this;
    }
}
