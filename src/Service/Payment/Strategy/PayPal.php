<?php

namespace App\Service\Payment\Strategy;

class Paypal implements PaymentStrategy
{
    private const BALANCE_INTEREST = 0.01;
    private const PAYMENT_RATE = 0.02;

    public function calculateInstallments(float $priceBase): array
    {
        $interest = $priceBase * self::BALANCE_INTEREST;
        $rate = $priceBase * self::PAYMENT_RATE;
        $total = $priceBase + $interest + $rate;
        return [
            'amount_base' => round($priceBase, 2),
            'balance_interest' => round($interest, 2),
            'payment_rate' => round($rate, 2),
            'total' => round($total, 2),
        ];

    }

}