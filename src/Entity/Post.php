<?php

namespace App\Entity;

use App\Doctrine\Common\Collections\ReadOnlyCollection;
use App\Dto\PostCreateDto;
use App\Dto\PostUpdateDto;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private Author $author;

    #[ORM\Column]
    private bool $ratingAllowed = false;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $ratingValue = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'posts')]
    private Collection $tags;

    private function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function isRatingAllowed(): bool
    {
        return $this->ratingAllowed;
    }

    public function getRatingValue(): ?int
    {
        return $this->ratingValue;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): ReadOnlyCollection
    {
        return new ReadOnlyCollection($this->tags);
    }

    public static function create(PostCreateDto $dto): self
    {
        $self = new self;

        $self->name = $dto->name;
        $self->author = $dto->author;
        $self->ratingAllowed = $dto->ratingAllowed;
        $self->ratingValue = $self->ratingAllowed ? $dto->ratingValue : null;
        $self->tags = new ArrayCollection($dto->tags);

        return $self;
    }

    public function updateWith(PostUpdateDto $dto): self
    {
        $this->name = $dto->name;
        $this->author = $dto->author;
        $this->ratingAllowed = $dto->ratingAllowed;
        $this->ratingValue = $this->ratingAllowed ? $dto->ratingValue : null;

        // This is a bit dirty - normally you'd want to do a diff. The simple version is here just here for the demo
        $this->tags = new ArrayCollection($dto->tags);

        return $this;
    }
}
