<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\SavingsBankRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SavingsBankRepository::class)]
#[\ApiPlatform\Core\Annotation\ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "security"              => "is_granted('" . User::ROLE_USER . "')",
            "normalization_context" => ["groups" => ["get:collection:savingsBank"]]
        ],
        "post" => [
            "method"                  => "POST",
            "security"                => "is_granted('" . User::ROLE_USER . "')",
            "denormalization_context" => ["groups" => ["post:collection:savingsBank"]],
            "normalization_context"   => ["groups" => ["empty"]],
        ]
    ],
    itemOperations: [
        "get" => [
            "method"                => "GET",
            "security"              => "is_granted('" . User::ROLE_USER . "')",
            "normalization_context" => ["groups" => ["get:item:savingsBank"]]
        ],
        "put" => [
            "method"                  => "PUT",
            "security"                => "is_granted('" . User::ROLE_USER . "')",
            "denormalization_context" => ["groups" => ["put:item:savingsBank"]],
            "normalization_context"   => ["groups" => ["get:item:savingsBank"]]
        ]
    ]
)]
class SavingsBank
{
    public function __construct()
    {
        $this->amount=0;
    }

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:savingsBank",
        "get:collection:savingsBank"
    ])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "get:item:savingsBank",
        "get:collection:savingsBank",
        "post:collection:savingsBank",
        "put:item:savingsBank"
    ])]
    private ?string $name = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Groups([
        "get:item:savingsBank",
        "get:collection:savingsBank",
        "post:collection:savingsBank"
    ])]
    private ?int $amount = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "get:item:savingsBank",
        "get:collection:savingsBank",
        "post:collection:savingsBank",
        "put:item:savingsBank"
    ])]
    private ?string $description = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'savingsBanks')]
    #[Groups([
        "get:item:savingsBank",
        "get:collection:savingsBank",
    ])]
    private ?User $user = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return $this
     */
    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

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
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
