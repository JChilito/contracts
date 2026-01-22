<?php

namespace App\Service\Payment\Strategy;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.payment_strategy')]
interface PaymentStrategy
{
    /**
     * This method calculates the installments based on the payment strategy
     * @param float $priceBase
     * @return array {amount_base: float, balance_interest: float, payment_rate: float, total: float}
     */
    public function calculateInstallments(float $priceBase): array;

    /**
     * This method is for the factory pattern to identify the type of payment strategy
     * @return string
     */
    public function gettype(): string;

}