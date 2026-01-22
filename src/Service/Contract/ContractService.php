<?php

namespace App\Service\Contract;

use App\DTO\Request\ContractRequest;
use App\Entity\Contract;
use App\DTO\Response\ContractResponse;
use App\Repository\ContractRepository;
use App\Service\Payment\Factory\PaymentFactory;
use App\ValueObject\Money;
use App\ValueObject\PaymentMethod;

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
        // TODO
        return null;
    }
}