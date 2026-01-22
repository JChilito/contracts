<?php

namespace App\ValueObject;

use Webmozart\Assert\Assert;

class Money
{
    private string $amount;

    public function __construct(string $amount)
    {
        // Validate that the amount is a valid decimal number
        Assert::numeric($amount, 'El monto debe ser un valor numérico válido.');

        // Validate that the amount is greater than or equal to zero
        Assert::greaterThanEq((float)$amount, 0, 'El monto debe ser mayor o igual a cero.');
        
        $this->amount = bcadd($amount, '0', 2); 
    }

    public function getAmount(): string
    {
        return $this->amount;
    }
}