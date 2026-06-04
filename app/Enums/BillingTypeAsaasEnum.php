<?php

namespace App\Enums;

enum BillingTypeAsaasEnum: string
{
    case CREDIT_CARD = 'CREDIT_CARD';
    case PIX = 'PIX';
    case BOLETO = 'BOLETO';

    public function getName(): string
    {
        return match ($this) {
            self::CREDIT_CARD => 'CARTÃO DE CRÉDITO',
            self::PIX => 'PIX',
            self::BOLETO => 'BOLETO',
        };
    }
}
