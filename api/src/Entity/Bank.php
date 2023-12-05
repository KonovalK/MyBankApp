<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BankRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BankRepository::class)]
#[\ApiPlatform\Core\Annotation\ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "security"              => "(is_granted('" . User::ROLE_ADMIN . "')) or (is_granted('" . User::ROLE_USER . "'))",
            "normalization_context" => ["groups" => ["get:collection:bank"]]
        ],
        "post" => [
            "method"                  => "POST",
            "security"                => "is_granted('" . User::ROLE_ADMIN . "')",
            "denormalization_context" => ["groups" => ["post:collection:bank"]],
            "normalization_context"   => ["groups" => ["get:item:bank"]]
        ]
    ],
    itemOperations: [
        "get" => [
            "method"                => "GET",
            "security"              => "(is_granted('" . User::ROLE_ADMIN . "')) or (is_granted('" . User::ROLE_USER . "'))",
            "normalization_context" => ["groups" => ["get:item:bank"]]
        ],
        "put" => [
            "method"                  => "PUT",
            "security"                => "is_granted('" . User::ROLE_ADMIN . "')",
            "denormalization_context" => ["groups" => ["put:item:bank"]],
            "normalization_context"   => ["groups" => ["get:item:bank"]]
        ],
        "delete" => [
            "method"                => "DELETE",
            "path"                  => "banks/delete/{id}",
            "security"              => "is_granted('" . User::ROLE_ADMIN . "')",
            "normalization_context" => ["groups" => ["bank:empty"]]
        ]
    ]
)]
class Bank
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:bank",
        "get:collection:bank",
    ])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:item:bank",
        "get:collection:bank",
        "post:collection:bank",
        "put:item:bank"
    ])]
    private ?string $bankName = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:item:bank",
        "get:collection:bank",
        "post:collection:bank",
        "put:item:bank"
    ])]
    private ?string $adress = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'bank', targetEntity: CardTemplate::class, cascade: ["remove"])]
    #[Groups([
        "get:item:bank",
        "get:collection:bank",
    ])]
    private Collection $cardTemplates;

    /**
     *
     */
    public function __construct()
    {
        $this->cardTemplates = new ArrayCollection();
        $this->initializeCollections();
    }

    /**
     * @return void
     */
    private function initializeCollections():void
    {
        foreach ($this->cardTemplates as $cardTemplate) {
            $cardTemplate->setBank($this);
        }
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
    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    /**
     * @param string $bankName
     * @return $this
     */
    public function setBankName(string $bankName): static
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdress(): ?string
    {
        return $this->adress;
    }

    /**
     * @param string $adress
     * @return $this
     */
    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * @return Collection<int, CardTemplate>
     */
    public function getCardTemplates(): Collection
    {
        return $this->cardTemplates;
    }

    /**
     * @param CardTemplate $cardTemplate
     * @return $this
     */
    public function addCardTemplate(CardTemplate $cardTemplate): static
    {
        if (!$this->cardTemplates->contains($cardTemplate)) {
            $this->cardTemplates->add($cardTemplate);
            $cardTemplate->setBank($this);
        }

        return $this;
    }

    /**
     * @param CardTemplate $cardTemplate
     * @return $this
     */
    public function removeCardTemplate(CardTemplate $cardTemplate): static
    {
        if ($this->cardTemplates->removeElement($cardTemplate)) {
            if ($cardTemplate->getBank() === $this) {
                $cardTemplate->setBank(null);
            }
        }

        return $this;
    }
}
