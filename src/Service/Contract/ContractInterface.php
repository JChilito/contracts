<?php

namespace App\Service\Contract;

use App\DTO\Request\ContractRequest;
use App\Entity\Contract;
use App\DTO\Response\ContractResponse;

interface ContractInterface
{
    /**
     * This method creates a new contract based on the provided request data.
     * @param ContractRequest $request
     * @return Contract
     */
    public function createContract(ContractRequest $request): Contract;

    public function projectInstallments(int $contractId, int $months): ?ContractResponse;
}