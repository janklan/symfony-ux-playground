<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $posts;

    public function __construct(?string $name = null)
    {
        $this->name = $name;
        $this->posts = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName() ?? 'New Author';
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

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $city): self
    {
        if (!$this->posts->contains($city)) {
            $this->posts->add($city);
            $city->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $city): self
    {
        if ($this->posts->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getAuthor() === $this) {
                $city->setAuthor(null);
            }
        }

        return $this;
    }
}
