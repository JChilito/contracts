<?php

namespace App\Service\Payment\Factory;

use App\Service\Payment\Strategy\PaymentStrategy;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class PaymentFactory
{
    /** @var PaymentStrategy[] */
    private array $strategies;

    public function __construct(
        #[TaggedIterator('app.payment_strategy')] iterable $strategies
    )
    {
        foreach ($strategies as $strategy) {
            $this->strategies[$strategy->gettype()] = $strategy;
        }
    }

    /**
     * This method returns the payment strategy based on the type
     * @param string $type
     * @return PaymentStrategy|null
     */
    public function getPaymentMethod(string $type): ?PaymentStrategy
    {
        if(!isset($this->strategies[$type])){
            throw new \InvalidArgumentException("Metodo de pago no soportado: " . $type);

        }
        return $this->strategies[$type];
    }
}