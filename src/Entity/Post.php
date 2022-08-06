<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Author $author = null;

    #[ORM\Column]
    private bool $ratingAllowed = false;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Assert\Range(min: 0, max: 3)]
    private ?int $ratingValue = null;

    public function __construct(?string $name = null)
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->getName() ?? 'New Post';
    }

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

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function isRatingAllowed(): bool
    {
        return $this->ratingAllowed;
    }

    public function setRatingAllowed(bool $ratingAllowed): self
    {
        $this->ratingAllowed = $ratingAllowed;

        return $this;
    }

    public function getRatingValue(): ?int
    {
        return $this->ratingValue;
    }

    public function setRatingValue(?int $ratingValue): self
    {
        $this->ratingValue = $ratingValue;

        return $this;
    }
}
