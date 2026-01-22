<?php

namespace App\Service\Payment\Strategy;

interface PaymentStrategy
{
    /**
     * This method calculates the installments based on the payment strategy
     * @param float $priceBase
     * @return array {amount_base: float, balance_interest: float, payment_rate: float, total: float}
     */
    public function calculateInstallments(float $priceBase): array;
}