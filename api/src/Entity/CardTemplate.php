<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\CardTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CardTemplateRepository::class)]
#[\ApiPlatform\Core\Annotation\ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "security"              => "(is_granted('" . User::ROLE_ADMIN . "')) or (is_granted('" . User::ROLE_USER . "'))",
            "normalization_context" => ["groups" => ["get:collection:cardTemplate"]]
        ],
        "post" => [
            "method"                  => "POST",
            "security"                => "is_granted('" . User::ROLE_ADMIN . "')",
            "denormalization_context" => ["groups" => ["post:collection:cardTemplate"]],
            "normalization_context"   => ["groups" => ["empty"]]
        ]
    ],
    itemOperations: [
        "get" => [
            "method"                => "GET",
            "security"              => "(is_granted('" . User::ROLE_ADMIN . "')) or (is_granted('" . User::ROLE_USER . "'))",
            "normalization_context" => ["groups" => ["get:item:cardTemplate"]]
        ],
        "put" => [
            "method"                  => "PUT",
            "security"                => "is_granted('" . User::ROLE_ADMIN . "')",
            "denormalization_context" => ["groups" => ["put:item:cardTemplate"]],
            "normalization_context"   => ["groups" => ["get:item:cardTemplate"]]
        ],
        "delete" => [
            "method"                => "DELETE",
            "path"                  => "card-template/delete/{id}",
            "security"              => "is_granted('" . User::ROLE_ADMIN . "')",
            "normalization_context" => ["groups" => ["cardTemplate:empty"]]
        ]
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    "bank" => "exact",
])]
class CardTemplate
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:cardTemplate",
        "get:collection:cardTemplate"
    ])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:item:cardTemplate",
        "get:collection:cardTemplate",
        "post:collection:cardTemplate",
        "put:item:cardTemplate"
    ])]
    private ?string $cardType = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "get:item:cardTemplate",
        "get:collection:cardTemplate",
        "post:collection:cardTemplate",
        "put:item:cardTemplate"
    ])]
    private ?string $cardBackgroundPhoto = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "get:item:cardTemplate",
        "get:collection:cardTemplate",
        "post:collection:cardTemplate",
        "put:item:cardTemplate"
    ])]
    private ?string $otherCardPropereties = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'template', targetEntity: Card::class, cascade: ["remove"])]
    private Collection $cards;

    /**
     * @var Bank|null
     */
    #[ORM\ManyToOne(inversedBy: 'cardTemplates')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        "get:item:cardTemplate",
        "get:collection:cardTemplate",
        "post:collection:cardTemplate",
        "put:item:cardTemplate"
    ])]
    private ?Bank $bank = null;

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
    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    /**
     * @param string $cardType
     * @return $this
     */
    public function setCardType(string $cardType): static
    {
        $this->cardType = $cardType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCardBackgroundPhoto(): ?string
    {
        return $this->cardBackgroundPhoto;
    }

    /**
     * @param string|null $cardBackgroundPhoto
     * @return $this
     */
    public function setCardBackgroundPhoto(?string $cardBackgroundPhoto): static
    {
        $this->cardBackgroundPhoto = $cardBackgroundPhoto;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOtherCardPropereties(): ?string
    {
        return $this->otherCardPropereties;
    }

    /**
     * @param string|null $otherCardPropereties
     * @return $this
     */
    public function setOtherCardPropereties(?string $otherCardPropereties): static
    {
        $this->otherCardPropereties = $otherCardPropereties;

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
            $card->setTemplate($this);
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
            if ($card->getTemplate() === $this) {
                $card->setTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @return Bank|null
     */
    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    /**
     * @param Bank|null $bank
     * @return $this
     */
    public function setBank(?Bank $bank): static
    {
        $this->bank = $bank;

        return $this;
    }
}
