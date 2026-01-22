<?php

namespace App\DTO\Response;

class ContractResponse
{
    public float $totalContractValue = 0;
    public float $totalBalanceInterest = 0;
    public float $totalRate = 0;
    public float $totalValueWithInterestAndRates = 0;

    /** @var InstallmentResponse[] */
    public array $installments = [];
}