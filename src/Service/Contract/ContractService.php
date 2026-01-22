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
use App\Mappers\ContractMapper;

class ContractService implements ContractInterface
{

    public function __construct(
        private readonly ContractRepository $contractRepository,
        private readonly PaymentFactory $paymentFactory,
        private readonly ContractMapper $contractMapper
    )
    {
    }

    public function createContract(ContractRequest $request): Contract
    {
        $contract = $this->contractMapper->toDomain($request);

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

        $tempTotalBalanceInterest = 0.0;
        $tempTotalRate = 0.0;
        $installmentsData = [];

        for ($month = 1; $month <= $months; $month++) {
            $calculation = $strategy->calculateInstallments($baseInstallmentsAmount);
            
            $dueDate = \DateTime::createFromInterface($contract->getContractDate());
            $dueDate->add(new \DateInterval("P{$month}M"));

            $installmentsData[] = [
                'quotaNumber' => $month,
                'expirationDate' => $dueDate->format('Y-m-d'),
                'amountBase' => $calculation['amount_base'],
                'balanceInterest' => $calculation['balance_interest'],
                'paymentRate' => $calculation['payment_rate'],
                'totalValue' => $calculation['total']
            ];

            $tempTotalBalanceInterest += $calculation['balance_interest'];
            $tempTotalRate += $calculation['payment_rate'];
        }

        $grandTotal = $totalValue + $tempTotalBalanceInterest + $tempTotalRate;

        return $this->contractMapper->toDTO(
            $contract,
            $tempTotalBalanceInterest,
            $tempTotalRate,
            $grandTotal,
            $installmentsData
        );

    }
}