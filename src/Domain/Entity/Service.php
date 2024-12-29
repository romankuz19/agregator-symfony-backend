<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(name: 'services')]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $username;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $text;

    #[ORM\Column(type: 'string', length: 255)]
    private string $category;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imgUrl = null;

    #[ORM\Column(type: 'integer')]
    private int $price = 0;

    #[ORM\Column(type: 'integer')]
    private int $views = 0;

    #[ORM\Column(type: 'integer')]
    private int $rating = 0;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'services')]
    #[ORM\JoinColumn(nullable: false)]
    private User $author;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'service', cascade: ['persist', 'remove'])]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'service', cascade: ['persist', 'remove'])]
    private Collection $reviews;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(?string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setService($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // Set the owning side to null if the relationship is optional
            if ($comment->getService() === $this) {
                $comment->setService(null);
            }
        }

        return $this;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setService($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // Set the owning side to null if the relationship is optional
            if ($review->getService() === $this) {
                $review->setService(null);
            }
        }

        return $this;
    }
}
