<?php

namespace App\Services\PaymentGateway\Contracts;

interface CreditCardInterface
{

    public function tokenize(array $data): array;
}
