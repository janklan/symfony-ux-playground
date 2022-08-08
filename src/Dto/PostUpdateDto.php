<?php

namespace App\Dto;

use App\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;

class PostUpdateDto extends PostCreateDto
{
    public static function createFrom(Post $source): self
    {
        $self = new self;

        $self->name = $source->getName();
        $self->author = $source->getAuthor();
        $self->ratingAllowed = $source->isRatingAllowed();
        $self->ratingValue = $source->getRatingValue();
        $self->tags = $source->getTags()->toArray();

        return $self;
    }
}
