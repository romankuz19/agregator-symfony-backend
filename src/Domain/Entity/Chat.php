<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
#[ORM\Table(name: 'chats')]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'chats')]
    #[ORM\JoinColumn(nullable: false)]
    private User $firstUser;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'chats')]
    #[ORM\JoinColumn(nullable: false)]
    private User $secondUser;

    #[ORM\OneToMany(targetEntity: ChatMessage::class, mappedBy: 'chat', cascade: ['persist', 'remove'])]
    private Collection $chatMessages;

    public function __construct()
    {
        $this->chatMessages = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getChatMessages(): Collection
    {
        return $this->chatMessages;
    }

    public function addChatMessage(ChatMessage $chatMessage): self
    {
        if (!$this->chatMessages->contains($chatMessage)) {
            $this->chatMessages[] = $chatMessage;
            $chatMessage->setChat($this);
        }

        return $this;
    }

    public function removeChatMessage(ChatMessage $chatMessage): self
    {
        if ($this->chatMessages->removeElement($chatMessage)) {
            // Set the owning side to null if the relationship is optional
            if ($chatMessage->getChat() === $this) {
                $chatMessage->setChat(null);
            }
        }

        return $this;
    }

    public function getFirstUser(): User
    {
        return $this->firstUser;
    }

    public function setFirstUser(User $firstUser): self
    {
        $this->firstUser = $firstUser;

        return $this;
    }

    public function getSecondUser(): User
    {
        return $this->secondUser;
    }

    public function setSecondUser(User $secondUser): self
    {
        $this->secondUser = $secondUser;

        return $this;
    }
}
