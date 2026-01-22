<?php

namespace App\DTO\Response;

readonly class InstallmentResponse
{
    public function __construct(
        public int $quotaNumber,     
        public string $expirationDate,
        public float $amountBase,
        public float $balanceInterest, 
        public float $paymentRate,    
        public float $totalValue    
    ) {}
}