<?php

namespace App\ValueObject;

use Webmozart\Assert\Assert;

class PaymentMethod
{
    public const PAYPAL = 'paypal';
    public const PAYONLINE = 'payonline';
    private const VALID_METHODS = [
        self::PAYPAL,
        self::PAYONLINE,
    ];

    private ?string $value = null;

    public function __construct(string $value)
    {
        $value = strtolower($value);
        Assert::inArray($value, self::VALID_METHODS, 'El método de pago no es válido.');
        $this->value = $value;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function equals(PaymentMethod $other): bool
    {
        return $this->value === $other->value;
    }
}