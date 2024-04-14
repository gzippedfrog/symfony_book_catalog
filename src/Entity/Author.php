<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ORM\UniqueConstraint(columns: ["first_name", "last_name", "patronymic"])]
#[UniqueEntity(
    fields: ["firstName", "lastName", "patronymic"],
    message: "Author with this first name, last name and patronymic combination already exist.",
    ignoreNull: false,
)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The author's first name must not be empty.")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "The author's first name must be between 2 and 255 characters long.",
        maxMessage: "The author's first name must be between 2 and 255 characters long."
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The author's last name must not be empty.")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "The author's last name must be between 2 and 255 characters long.",
        maxMessage: "The author's last name must be between 2 and 255 characters long."
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "The author's patronymic must be between 2 and 255 characters long.",
        maxMessage: "The author's patronymic must be between 2 and 255 characters long."
    )]
    private ?string $patronymic = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'authors')]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): static
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        $this->books->removeElement($book);

        return $this;
    }

    public function __toString(): string
    {
        return trim($this->firstName.' '.$this->lastName.' '.$this->patronymic);
    }
}
