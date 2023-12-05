<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Action\CreateCardAction;
use App\Action\CreateUserAction;
use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CardRepository::class)]
#[\ApiPlatform\Core\Annotation\ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "security"              => "(is_granted('" . User::ROLE_ADMIN . "')) or (is_granted('" . User::ROLE_USER . "'))",
            "normalization_context" => ["groups"=> ["get:collection:card"]],
        ],
        "post" => [
            "method"                  => "POST",
            "security"                => "(is_granted('" . User::ROLE_ADMIN . "')) or (is_granted('" . User::ROLE_USER . "'))",
            "denormalization_context" => ["groups" => ["post:collection:card"]],
            "normalization_context"   => ["groups" => ["empty"]],
            "controller"              => CreateCardAction::class
        ]
    ],
    itemOperations: [
        "get" => [
            "method"                => "GET",
            "security"              => "(is_granted('" . User::ROLE_ADMIN . "')) or (is_granted('" . User::ROLE_USER . "'))",
            "normalization_context" => ["groups" => ["get:collection:card"]],
        ],
        "put" => [
            "method"                  => "PUT",
            "security"                => "(is_granted('" . User::ROLE_ADMIN . "')) or (is_granted('" . User::ROLE_USER . "'))",
            "denormalization_context" => ["groups" => ["put:item:card"]],
            "normalization_context"   => ["groups" => ["get:item:card"]]
        ]
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    "isVerified" => "exact",
])]
class Card implements \JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:card",
        "get:collection:card"
    ])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:item:card",
        "get:collection:card"
    ])]
    private ?string $cardNumber = null;
    /**
     * @var int|null
     */
    #[Groups([
        "post:collection:card",
        "put:item:card",
    ])]
    #[ORM\Column]
    private ?int $pin = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:item:card",
        "get:collection:card"
    ])]
    private ?string $expirationDate = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $cvv = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Groups([
        "get:item:card",
        "get:collection:card",
        "post:collection:card",
        "put:item:card"
    ])]
    private ?int $balance = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'cards')]
    #[Groups([
        "get:item:card",
        "get:collection:card"
    ])]
    private ?User $user = null;

    /**
     * @var CardTemplate|null
     */
    #[ORM\ManyToOne(inversedBy: 'cards')]
    #[Groups([
        "get:item:card",
        "get:collection:card",
        "post:collection:card",
        "put:item:card"
    ])]
    private ?CardTemplate $template = null;

    /**
     * @var int|null
     */
    #[Groups([
        "get:item:card",
        "get:collection:card",
        "put:item:card"
    ])]
    #[ORM\Column]
    private ?int $isVerified = 0;
    /**
     * @var ExchangeRate|null
     */
    #[Groups([
        "get:item:card",
        "get:collection:card",
        "post:collection:card"
    ])]
    #[ORM\ManyToOne(inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExchangeRate $rate = null;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    /**
     * @param string $cardNumber
     * @return $this
     */
    public function setCardNumber(string $cardNumber): static
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPin(): ?int
    {
        return $this->pin;
    }

    /**
     * @param int $pin
     * @return $this
     */
    public function setPin(int $pin): static
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }

    /**
     * @param string $expirationDate
     * @return $this
     */
    public function setExpirationDate(string $expirationDate): static
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(int $cvv): static
    {
        $this->cvv = $cvv;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBalance(): ?int
    {
        return $this->balance;
    }

    /**
     * @param int|null $balance
     */
    public function setBalance(?int $balance): void
    {
        $this->balance = $balance;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTemplate(): ?CardTemplate
    {
        return $this->template;
    }

    public function setTemplate(?CardTemplate $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id'          => $this->id,
            'cardNumber'       => $this->getCardNumber(),
            'expirationDate'        => $this->getExpirationDate(),
            'balance' => $this->getBalance(),
            'bank'      => $this->getTemplate()->getBank()->getBankName(),
            'backImage'    => $this->getTemplate()->getCardBackgroundPhoto(),
        ];
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getRate(): ?ExchangeRate
    {
        return $this->rate;
    }

    public function setRate(?ExchangeRate $rate): static
    {
        $this->rate = $rate;

        return $this;
    }
}
