<?php

namespace App\DTO\Response;

class ContractResponse
{
    public string $totalContractValue = '';
    public string $totalBalanceInterest = '';
    public string $totalRate = '';
    public string $totalValueWithInterestAndRates = '';

    /** @var InstallmentResponse[] */
    public array $installments = [];
}