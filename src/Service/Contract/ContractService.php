<?php

namespace App\Service\Contract;

use App\DTO\Request\ContractRequest;
use App\Entity\Contract;
use App\DTO\Response\ContractResponse;
use App\Repository\ContractRepository;
use App\Service\Payment\Factory\PaymentFactory;
use App\ValueObject\Money;
use App\ValueObject\PaymentMethod;
use App\DTO\Response\InstallmentResponse;

class ContractService implements ContractInterface
{

    public function __construct(
        private readonly ContractRepository $contractRepository,
        private readonly PaymentFactory $paymentFactory
    )
    {
    }

    public function createContract(ContractRequest $request): Contract
    {
        $contract = new Contract();
        $contract->setContractNumber((int)$request->contractNumber);
        $contract->setContractDate($request->contractDate);
        $contract->setTotalValue(new Money((string)$request->totalValue));
        $contract->setPaymentMethod(new PaymentMethod($request->paymentMethod));

        $this->contractRepository->save($contract, true);

        return $contract;
    }

    public function projectInstallments(int $contractId, int $months): ?ContractResponse
    {
        $contract = $this->contractRepository->find($contractId);
        if (!$contract || !$contract->getTotalValue() || !$contract->getPaymentMethod()) {
            return null;
        }

        if($months <= 0){
            throw new \InvalidArgumentException("La cantidad de meses debe ser mayor a cero.");
        }

        $paymentMethod = $contract->getPaymentMethod()->getValue();
        $totalValue = $contract->getTotalValue()->getAmount();

        // Get the appropriate payment strategy
        $strategy = $this->paymentFactory->getPaymentMethod($paymentMethod);
        $baseInstallmentsAmount = $totalValue / $months;

        $response = new ContractResponse();
        $response->totalContractValue = $totalValue;

        for($month = 1; $month <= $months; $month++){
            $calculation = $strategy->calculateInstallments($baseInstallmentsAmount);

            $dueDate = \DateTime::createFromInterface($contract->getContractDate());
            $dueDate->add(new \DateInterval("P{$month}M"));

            $response->installments[] = new InstallmentResponse(
                quotaNumber: $month,
                expirationDate: $dueDate->format('Y-m-d'),
                amountBase: $calculation['amount_base'],
                balanceInterest: $calculation['balance_interest'],
                paymentRate: $calculation['payment_rate'],
                totalValue: $calculation['total']
            );

            $response->totalBalanceInterest += $calculation['balance_interest'];
            $response->totalRate += $calculation['payment_rate'];

        }

        $response->totalBalanceInterest = round($response->totalBalanceInterest, 2);
        $response->totalRate = round($response->totalRate, 2);

        $response->totalValueWithInterestAndRates = round (
            $response->totalContractValue +
            $response->totalBalanceInterest +
            $response->totalRate,
            2
        );

        return $response;

    }
}