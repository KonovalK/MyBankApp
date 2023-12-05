<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\EntityListener\TransactionEntityListener;
use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[\ApiPlatform\Core\Annotation\ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "security"              => "is_granted('" . User::ROLE_USER . "')",
            "normalization_context" => ["groups" => ["get:collection:transaction"]]
        ],
        "post" => [
            "method"                  => "POST",
            "security"                => "!is_granted('" . User::ROLE_USER . "')",
            "denormalization_context" => ["groups" => ["post:collection:transaction"]],
            "normalization_context"   => ["groups" => ["empty"]]
        ]
    ],
    itemOperations: [
        "get" => [
            "method"                => "GET",
            "security"              => "(is_granted('" . User::ROLE_ADMIN . "')) or (is_granted('" . User::ROLE_USER . "') and object == user)",
            "normalization_context" => ["groups" => ["get:item:transaction"]]
        ],
        "put" => [
            "method"                  => "PUT",
            "security"                => "object == user",
            "denormalization_context" => ["groups" => ["put:item:transaction"]],
            "normalization_context"   => ["groups" => ["get:item:transaction"]]
        ]
    ]
)]
#[ORM\EntityListeners([TransactionEntityListener::class])]
class Transaction implements \JsonSerializable
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:transaction",
        "get:collection:transaction"
    ])]
    private int $id;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Groups([
        "get:item:transaction",
        "get:collection:transaction",
        "post:collection:transaction",
        "put:item:transaction"
    ])]
    private ?int $summa = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([
        "get:item:transaction",
        "get:collection:transaction"
    ])]
    private ?\DateTimeInterface $date = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "get:item:transaction",
        "get:collection:transaction",
        "post:collection:transaction",
        "put:item:transaction"
    ])]
    private ?string $description = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        "get:item:transaction",
        "get:collection:transaction"
    ])]
    private ?User $sender = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        "get:item:transaction",
        "get:collection:transaction",
        "post:collection:transaction",
        "put:item:transaction"
    ])]
    private ?User $receiver = null;
    /**
     * @var string|null
     */
    #[Groups([
        "get:item:transaction",
        "get:collection:transaction",
        "post:collection:transaction",
        "put:item:transaction"
    ])]
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $senderCard = null;
    /**
     * @var string|null
     */
    #[Groups([
        "get:item:transaction",
        "get:collection:transaction",
        "post:collection:transaction",
        "put:item:transaction"
    ])]
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $receiverCard = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getSumma(): ?int
    {
        return $this->summa;
    }

    /**
     * @param int $summa
     * @return $this
     */
    public function setSumma(int $summa): self
    {
        $this->summa = $summa;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getSender(): ?User
    {
        return $this->sender;
    }

    /**
     * @param User|null $sender
     * @return $this
     */
    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    /**
     * @param User|null $receiver
     * @return $this
     */
    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize():array
    {
        return [
            'id'          => $this->id,
            'summa'       => $this->summa,
            'date'        => $this->date->getTimestamp(),
            'description' => $this->description,
            'sender'      => $this->sender->getName()." ".$this->sender->getSurname(),
            'receiver'    => $this->receiver->getName()." ".$this->receiver->getSurname(),
        ];
    }

    /**
     * @return string|null
     */
    public function getSenderCard(): ?string
    {
        return $this->senderCard;
    }

    /**
     * @param string $senderCard
     * @return $this
     */
    public function setSenderCard(string $senderCard): static
    {
        $this->senderCard = $senderCard;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReceiverCard(): ?string
    {
        return $this->receiverCard;
    }

    /**
     * @param string $receiverCard
     * @return $this
     */
    public function setReceiverCard(string $receiverCard): static
    {
        $this->receiverCard = $receiverCard;

        return $this;
    }

}
