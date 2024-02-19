<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $value = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    private ?User $users = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    private ?Course $courses = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getCourses(): ?Course
    {
        return $this->courses;
    }

    public function setCourses(?Course $courses): static
    {
        $this->courses = $courses;

        return $this;
    }
}
