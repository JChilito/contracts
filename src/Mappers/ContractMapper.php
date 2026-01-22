<?php

namespace App\Mappers;

use App\DTO\Request\ContractRequest;
use App\DTO\Response\ContractResponse;
use App\DTO\Response\InstallmentResponse;
use App\Entity\Contract;
use App\ValueObject\Money;
use App\ValueObject\PaymentMethod;

class ContractMapper
{

    /**
     * This method converts a ContractRequest DTO into a Contract domain entity.
     */
    public function toDomain(ContractRequest $request): Contract
    {
        $contract = new Contract();
        $contract->setContractNumber((int)$request->contractNumber);
        $contract->setContractDate($request->contractDate);
        $contract->setTotalValue(new Money((string)$request->totalValue));
        $contract->setPaymentMethod(new PaymentMethod($request->paymentMethod));

        return $contract;
    }

    /**
     * This method converts a Contract domain entity into a ContractResponse DTO.
     */
    public function toDTO(
        Contract $contract, 
        float $totalBalanceInterest, 
        float $totalRate, 
        float $grandTotal, 
        array $installmentsData 
    ): ContractResponse 
    {
        $response = new ContractResponse();

        $response->totalContractValue = $this->formatMoney((float)$contract->getTotalValue()->getAmount());
        $response->totalBalanceInterest = $this->formatMoney($totalBalanceInterest);
        $response->totalRate = $this->formatMoney($totalRate);
        $response->totalValueWithInterestAndRates = $this->formatMoney($grandTotal);

        foreach ($installmentsData as $data) {
            $response->installments[] = new InstallmentResponse(
                quotaNumber: $data['quotaNumber'],
                expirationDate: $data['expirationDate'],
                amountBase: $this->formatMoney($data['amountBase']),
                balanceInterest: $this->formatMoney($data['balanceInterest']),
                paymentRate: $this->formatMoney($data['paymentRate']),
                totalValue: $this->formatMoney($data['totalValue'])
            );
        }

        return $response;
    }

    private function formatMoney(float $value): string
    {
        return '$ ' . number_format($value, 2, ',', '.');
    }
}