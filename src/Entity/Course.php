<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 600, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'createdCourses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teacher $author = null;

    #[ORM\Column(length: 10000)]
    private ?string $content = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'courses')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'participateCourses')]
    private Collection $participateUsers;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->participateUsers= new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?Teacher
    {
        return $this->author;
    }

    public function setAuthor(?Teacher $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipateUsers(): Collection
    {
        return $this->participateUsers;
    }

    public function addParticipateUsers(User $user): static
    {
        if (!$this->participateUsers->contains($user)) {
            $this->participateUsers->add($user);
            $user->addParticipateCourse($this);
        }

        return $this;
    }

    public function removeParticipateUsers(User $user): static
    {
        if ($this->participateUsers->removeElement($user)) {
            $user->removeParticipateCourse($this);
        }

        return $this;
    }
}
