<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Trait\DateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use DateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user'])]
    private ?int $id = null;

    #[ORM\Column(type: 'json')]
    #[Groups(['user'])]
    private $roles = [];

    #[ORM\Column(length: 255)]
    #[Groups(['user'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user'])]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['user'])]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['auth'])]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Expense::class, orphanRemoval: true)]
    // #[Groups(['user'])]
    #[MaxDepth(1)]
    private Collection $expenses;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): self
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setUser($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): self
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getUser() === $this) {
                $expense->setUser(null);
            }
        }

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function toArray(): array
    {
        // $expenses = [];
        // if (! empty($this->getExpenses())) {
        //     foreach ($this->getExpenses() as $expense) {
        //         $expenses[] = [
        //             'id' => $expense->getId(),
        //             'type' => $expense->getType(),
        //             'amount' => $expense->getAmount(),
        //             'date' => $expense->getDate(),
        //             'created_at' => $expense->getCreatedAt(),
        //             'updated_at' => $expense->getUpdatedAt(),
        //         ];
        //     }
        // }
        return [
            'id' => $this->getId(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'birthday' => $this->getBirthday(),
            'email' => $this->getEmail(),
            'roles' => $this->getRoles(),
            // 'expenses' => $expenses,
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
        ];
    }
}
