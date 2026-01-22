<?php

namespace App\Controller;

use App\Service\Contract\ContractInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\Request\ContractRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/contracts')]
final class ContractController extends AbstractController
{
    public function __construct(
        private readonly ContractInterface $contractInterface
    )
    {
    }

    #[Route('', name: 'create_contract', methods: ['POST'])]
    public function createContract(#[MapRequestPayload] ContractRequest $request): JsonResponse
    {
        $newContract = $this->contractInterface->createContract($request);
        return $this->json([
            'id' => $newContract->getId(),
            'contractNumber' => $newContract->getContractNumber(),
            'contractDate' => $newContract->getContractDate()->format('Y-m-d'),
            'totalValue' => $newContract->getTotalValue()->getAmount(),
            'paymentMethod' => $newContract->getPaymentMethod()->getValue()
        ], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{contractId}/installments/{months}', name: 'api_contract_project', methods: ['GET'])]
    public function projectInstallments(int $contractId, int $months): JsonResponse
    {
        try {
            $responseDTO = $this->contractInterface->projectInstallments($contractId, $months);

            if (!$responseDTO) {
                return $this->json(['error' => 'Contrato no encontrado o datos incompletos'], Response::HTTP_NOT_FOUND);
            }

            return $this->json($responseDTO);

        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
