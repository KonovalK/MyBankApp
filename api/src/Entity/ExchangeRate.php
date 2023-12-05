<?php

namespace App\Entity;

use App\Action\CreateCardAction;
use App\Repository\ExchangeRateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class)]
#[\ApiPlatform\Core\Annotation\ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "security"              => "is_granted('" . User::ROLE_USER . "')",
            "normalization_context" => [
                "groups"                => ["get:collection:rate"],
                "disable_type_enforcement" => true,
            ],

        ],
        "post" => [
            "method"                  => "POST",
            "security"                => "is_granted('" . User::ROLE_ADMIN . "')",
            "denormalization_context" => ["groups" => ["post:collection:rate"]],
            "normalization_context"   => ["groups" => ["empty"]],
            "controller"              => CreateCardAction::class
        ]
    ],
    itemOperations: [
        "get" => [
            "method"                => "GET",
            "security"              => "is_granted('" . User::ROLE_USER . "')",
            "normalization_context" => [
                "groups"                => ["get:collection:rate"],
                "disable_type_enforcement" => true,
            ],
        ],
        "put" => [
            "method"                  => "PUT",
            "security"                => "is_granted('" . User::ROLE_ADMIN . "')",
            "denormalization_context" => ["groups" => ["put:item:rate"]],
            "normalization_context"   => ["groups" => ["get:item:rate"]]
        ]
    ]
)]
class ExchangeRate
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:rate",
        "get:collection:rate",
    ])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[Groups([
        "get:item:rate",
        "get:collection:rate",
        "post:collection:rate",
        "put:item:rate"
    ])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var float|null
     */
    #[Groups([
        "get:item:rate",
        "get:collection:rate",
        "post:collection:rate",
        "put:item:rate"
    ])]
    #[ORM\Column]
    private ?float $weight = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'rate', targetEntity: Card::class)]
    private Collection $cards;

    /**
     *
     */
    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     * @return $this
     */
    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    /**
     * @param Card $card
     * @return $this
     */
    public function addCard(Card $card): static
    {
        if (!$this->cards->contains($card)) {
            $this->cards->add($card);
            $card->setRate($this);
        }

        return $this;
    }

    /**
     * @param Card $card
     * @return $this
     */
    public function removeCard(Card $card): static
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getRate() === $this) {
                $card->setRate(null);
            }
        }

        return $this;
    }
}
