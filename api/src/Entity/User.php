<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Action\CreateUserAction;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "security"              => "is_granted('" . User::ROLE_ADMIN . "')",
            "normalization_context" => ["groups" => ["get:collection:user"]]
        ],
        "post" => [
            "method"                  => "POST",
            "security"                => "!(is_granted('" . User::ROLE_USER . "'))",
            "denormalization_context" => ["groups" => ["post:collection:user"]],
            "normalization_context"   => ["groups" => ["empty"]],
            "controller"              => CreateUserAction::class
        ]
    ],
    itemOperations: [
        "get" => [
            "method"                => "GET",
            "security"              => "(is_granted('" . User::ROLE_ADMIN . "')) or (is_granted('" . User::ROLE_USER . "') and object == user)",
            "normalization_context" => ["groups" => ["get:item:user"]]
        ],
        "put" => [
            "method"                  => "PUT",
            "security"                => "(is_granted('" . User::ROLE_USER . "')",
            "denormalization_context" => ["groups" => ["put:item:user"]],
            "normalization_context"   => ["groups" => ["get:item:user"]]
        ]
    ]
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ["email"], message: "Email is already in use")]
#[UniqueEntity(fields: ["phoneNumber"], message: "Phone number is already in use")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    /**
     *
     */
    public const ROLE_USER    = "ROLE_USER";
    /**
     *
     */
    public const ROLE_ADMIN   = "ROLE_ADMIN";
    /**
     *
     */
    public const ROLE_GUEST   = "ROLE_GUEST";

    /**
     * @var Uuid
     */
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[Groups([
        "get:collection:user"
    ])]
    private Uuid $id;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 180, unique: true)]
    #[Email]
    #[NotBlank]
    #[Groups([
        "get:item:user",
        "get:collection:user",
        "post:collection:user",
        "put:item:user"
    ])]
    private ?string $email = null;

    /**
     * @var string[]
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    #[Length(min: 8, minMessage: "Password must be at least {{ limit }} characters long")]
    #[Groups([
        "post:collection:user"
    ])]
    private ?string $password = null;

    /**
     * @var string|null
     */
    #[Regex(
        pattern: "/\+\s*(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\s*\d{1,14}$/",
        message: 'Your name cannot contain a number',
        match: false,
    )]
    #[ORM\Column(length: 255, unique: true)]
    #[NotBlank]
    #[Groups([
        "get:item:user",
        "get:collection:user",
        "put:item:user",
        "post:collection:user"
    ])]
    private ?string $phoneNumber = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:item:user",
        "get:collection:user",
        "put:item:user",
        "post:collection:user"
    ])]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:item:user",
        "get:collection:user",
        "put:item:user",
        "post:collection:user"
    ])]
    private ?string $surname = null;

    /**
     * @var Verification|null
     */
    #[ORM\OneToOne(mappedBy: 'user', cascade: [
        'persist',
        'remove'
    ])]
    private ?Verification $verification = null;

    /**
     * @var bool
     */
    #[ORM\Column]
    private bool $isVerified = false;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Transaction::class)]
    private Collection $transactions;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Card::class)]
    private Collection $cards;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SavingsBank::class)]
    private Collection $savingsBanks;

    /**
     * User constructor
     */
    public function __construct()
    {
        $this->roles = [self::ROLE_GUEST];
        $this->transactions = new ArrayCollection();
        $this->cards = new ArrayCollection();
        $this->savingsBanks = new ArrayCollection();
    }

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
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
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return $this
     */
    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Verification|null
     */
    public function getVerification(): ?Verification
    {
        return $this->verification;
    }

    /**
     * @param Verification $verification
     * @return $this
     */
    public function setVerification(Verification $verification): self
    {
        // set the owning side of the relation if necessary
        if ($verification->getUser() !== $this) {
            $verification->setUser($this);
        }

        $this->verification = $verification;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    /**
     * @param bool $isVerified
     * @return $this
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * @param Transaction $transaction
     * @return $this
     */
    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setSender($this);
        }

        return $this;
    }

    /**
     * @param Transaction $transaction
     * @return $this
     */
    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getSender() === $this) {
                $transaction->setSender(null);
            }
        }

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
            $card->setUser($this);
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
            if ($card->getUser() === $this) {
                $card->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SavingsBank>
     */
    public function getSavingsBanks(): Collection
    {
        return $this->savingsBanks;
    }

    /**
     * @param SavingsBank $savingsBank
     * @return $this
     */
    public function addSavingsBank(SavingsBank $savingsBank): static
    {
        if (!$this->savingsBanks->contains($savingsBank)) {
            $this->savingsBanks->add($savingsBank);
            $savingsBank->setUser($this);
        }

        return $this;
    }

    /**
     * @param SavingsBank $savingsBank
     * @return $this
     */
    public function removeSavingsBank(SavingsBank $savingsBank): static
    {
        if ($this->savingsBanks->removeElement($savingsBank)) {
            // set the owning side to null (unless already changed)
            if ($savingsBank->getUser() === $this) {
                $savingsBank->setUser(null);
            }
        }

        return $this;
    }

}