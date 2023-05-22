<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use App\Trait\DateTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Expense
{
    use DateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['expense'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['expense'])]
    #[Assert\NotBlank]
    private ?int $type = null;

    #[ORM\Column]
    // #[Groups(['expense'])]
    #[Assert\NotBlank()]
    #[Assert\PositiveOrZero()]
    private ?float $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    // #[Groups(['expense'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'expenses', cascade: ['persist'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    // #[Groups(["expense"])]
    #[MaxDepth(1)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'expenses', cascade: ['persist'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false)]
    // #[Groups(["expense"])]
    private ?Company $company = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->created_at = new \DateTime();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'amount' => $this->getAmount(),
            'date' => $this->getDate()->format('Y-m-d'),
            'user' => $this->getUser()->toArray(),
            'company' => $this->getCompany()->toArray(),
        ];
    }
}
