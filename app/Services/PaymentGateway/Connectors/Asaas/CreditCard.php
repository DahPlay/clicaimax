<?php

namespace App\Services\PaymentGateway\Connectors\Asaas;

use App\Services\PaymentGateway\Contracts\AdapterInterface;
use App\Services\PaymentGateway\Contracts\CreditCardInterface;

class CreditCard implements CreditCardInterface
{

    public function __construct(
        public AdapterInterface $http,
    ) {}

    public function tokenize(array $data): array
    {
        return $this->http->post('/creditCard/tokenizeCreditCard', $data);
    }
}
