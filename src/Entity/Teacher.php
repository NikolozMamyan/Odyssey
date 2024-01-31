<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Course::class)]
    private Collection $createdCourses;

    public function __construct()
    {
        $this->createdCourses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection<int, Course>
     */
    public function getCreatedCourses(): Collection
    {
        return $this->createdCourses;
    }

    public function addCreatedCourse(Course $createdCourse): static
    {
        if (!$this->createdCourses->contains($createdCourse)) {
            $this->createdCourses->add($createdCourse);
            $createdCourse->setAuthor($this);
        }

        return $this;
    }

    public function removeCreatedCourse(Course $createdCourse): static
    {
        if ($this->createdCourses->removeElement($createdCourse)) {
            // set the owning side to null (unless already changed)
            if ($createdCourse->getAuthor() === $this) {
                $createdCourse->setAuthor(null);
            }
        }

        return $this;
    }
}
