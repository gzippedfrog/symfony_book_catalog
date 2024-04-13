<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\UniqueConstraint(columns: ["title", "isbn"])]
#[ORM\UniqueConstraint(columns: ["title", "year"])]
#[UniqueEntity(fields: ["title", "isbn"], message: "Book with this title and ISBN combination already exist.")]
#[UniqueEntity(fields: ["title", "year"], message: "Book with this title and year combination already exist.")]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The book's title must not be empty.")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "The book's title must be between 2 and 255.",
        maxMessage: "The book's title must be between 2 and 255."
    )]
    private ?string $title = null;

    #[ORM\Column(length: 4)]
    #[Assert\NotBlank(message: "The book's year must not be empty.")]
    #[Assert\GreaterThan(0, message: "The book's year must be greater than 0.")]
    private ?string $year = null;

    #[ORM\Column(length: 17)]
    #[Assert\NotBlank(message: "The book's ISBN must not be empty.")]
    #[Assert\Isbn]
    private ?string $isbn = null;

    /**
     * @var Collection<int, Author>
     */
    #[ORM\ManyToMany(targetEntity: Author::class, mappedBy: 'books')]
    private Collection $authors;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
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

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        if ($this->authors->removeElement($author)) {
            $author->removeBook($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf("%s (%s)", $this->title, $this->year);
    }
}
