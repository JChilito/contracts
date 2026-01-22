<?php

namespace App\Entity;

use App\Repository\ContractRepository;
use App\ValueObject\Money;
use App\ValueObject\PaymentMethod;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractRepository::class)]
class Contract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $contractDate = null;

    #[ORM\Embedded(class: Money::class)]
    private ?Money $totalValue = null;

    #[ORM\Embedded(class: PaymentMethod::class)]
    private ?PaymentMethod $paymentMethod = null;

    #[ORM\Column]
    private ?int $contractNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContractDate(): ?\DateTimeImmutable
    {
        return $this->contractDate;
    }

    public function setContractDate(\DateTimeImmutable $contractDate): static
    {
        $this->contractDate = $contractDate;

        return $this;
    }

    public function getTotalValue(): ?Money
    {
        return $this->totalValue;
    }

    public function setTotalValue(Money $totalValue): static
    {
        $this->totalValue = $totalValue;
        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(PaymentMethod $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getContractNumber(): ?int
    {
        return $this->contractNumber;
    }

    public function setContractNumber(int $contractNumber): static
    {
        $this->contractNumber = $contractNumber;

        return $this;
    }
}
