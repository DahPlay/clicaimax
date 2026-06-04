<?php

use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Crypt;

if (!function_exists('decryptYcPwd')) {
    /**
     * Descriptografa o valor armazenado em session('yc_pwd') de forma defensiva.
     *
     * Hoje gravamos sempre com Crypt::encryptString() (string crua). Mas valores
     * antigos podem ter sido escritos com Crypt::encrypt() (serialização PHP),
     * gerando "s:N:\"...\";" depois do decrypt. Aqui detectamos e tratamos os
     * dois casos sem quebrar.
     */
    function decryptYcPwd($encrypted): ?string
    {
        if ($encrypted === null || $encrypted === '') {
            return null;
        }

        try {
            // Tentamos como string (encryptString -> decryptString)
            $value = Crypt::decryptString($encrypted);
        } catch (\Throwable $e) {
            try {
                // Fallback: dados antigos gravados com Crypt::encrypt()
                $value = Crypt::decrypt($encrypted);
            } catch (\Throwable $e2) {
                return null;
            }
        }

        // Se vier no formato serializado "s:N:\"...\";", desempacotar.
        if (is_string($value) && preg_match('/^s:\d+:".*";$/s', $value)) {
            $unserialized = @unserialize($value);
            if (is_string($unserialized)) {
                return $unserialized;
            }
        }

        return is_string($value) ? $value : null;
    }
}

if (!function_exists('get_gateway')) {
    function get_gateway()
    {
        $adapter = app(AsaasConnector::class);
        return app(Gateway::class, ['adapter' => $adapter]);
    }
}

if (!function_exists('clear_string')) {
    function clear_string(?string $string): ?string
    {
        if (is_null($string)) {
            return null;
        }

        return (string) preg_replace('/[^A-Za-z0-9]/', '', $string);
    }
}

if (!function_exists('sanitize')) {
    function sanitize(?string $data): ?string
    {
        if (is_null($data)) {
            return null;
        }
        return clear_string($data);
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($value, $decimals = 2, $decimalSeparator = ',', $thousandsSeparator = '.'): ?string
    {
        return number_format($value, $decimals, $decimalSeparator, $thousandsSeparator);
    }
}

if (!function_exists('generateLast12Months')) {
    function generateLast12Months(?string $type = null): array
    {
        $currentDate = CarbonImmutable::now();
        $labels = [];

        for ($i = 0; $i < 12; $i++) {
            $year = $currentDate->subMonths($i)->year;
            $month_number = $currentDate->subMonths($i)->month;

            if ($type === 'labels') {
                $labels[$i] = $currentDate->subMonths($i)->translatedFormat('M');
            } else {
                $labels[$i]['year'] = $year;
                $labels[$i]['month'] = $month_number;
            }
        }
        return array_reverse($labels);
    }
}
