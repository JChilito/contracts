<?php

namespace App\DTO\Response;

readonly class InstallmentResponse
{
    public function __construct(
        public int $quotaNumber,     
        public string $expirationDate,
        public string $amountBase,
        public string $balanceInterest, 
        public string $paymentRate,    
        public string $totalValue    
    ) {}
}