<?php

namespace App\DTO\Request;

use App\ValueObject\PaymentMethod;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ContractRequest
{

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 50)]
        public ?string $contractNumber = null,

        #[Assert\NotBlank]
        #[Assert\Type(\DateTimeImmutable::class)]
        public ?\DateTimeImmutable $contractDate = null,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public ?float $totalValue = null,

        #[Assert\NotBlank]
        #[Assert\Choice(choices: [PaymentMethod::PAYPAL, PaymentMethod::PAYONLINE])]
        public ?string $paymentMethod = null
    )
    {

    }

}