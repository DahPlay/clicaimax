<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway;

use App\Services\PaymentGateway\Connectors\Asaas\Customer;
use App\Services\PaymentGateway\Connectors\Asaas\CreditCard;
use App\Services\PaymentGateway\Connectors\Asaas\Payment;
use App\Services\PaymentGateway\Connectors\Asaas\Subscription;
use App\Services\PaymentGateway\Connectors\Asaas\WebHook;
use App\Services\PaymentGateway\Contracts\AdapterInterface;

class Gateway
{

    public function __construct(
        public AdapterInterface $adapter
    ) {}

    public function customer(): Customer
    {
        return new Customer($this->adapter);
    }

    public function creditCard(): CreditCard
    {
        return new CreditCard($this->adapter);
    }

    public function payment(): Payment
    {
        return new Payment($this->adapter);
    }

    public function subscription(): Subscription
    {
        return new Subscription($this->adapter);
    }

    public function webhook(): WebHook
    {
        return new WebHook($this->adapter);
    }
}
