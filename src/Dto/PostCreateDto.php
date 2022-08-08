<?php

namespace App\Dto;

use App\Entity\Tag;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Author;

class PostCreateDto
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public Author $author;

    #[Assert\Type('boolean')]
    public bool $ratingAllowed = false;

    #[Assert\Range(min: 0, max: 3)]
    public ?int $ratingValue = null;

    /** @var array<int, Tag> */
    public array $tags;
}
