<?php

namespace App\Enums;

enum BillingTypeAsaasEnum: string
{
    // case BOLETO = 'BOLETO';
    case CREDIT_CARD = 'CREDIT_CARD';
    // case PIX = 'PIX';

    public function getName(): string
    {
        return match ($this) {
            // self::BOLETO => 'BOLETO',
            self::CREDIT_CARD => 'CARTÃO DE CRÉDITO',
            // self::PIX => 'PIX',
        };
    }
}
