<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

/**
 * Marca todos os planos existentes para aceitarem CREDIT_CARD, PIX e BOLETO.
 *
 * Rode com:
 *   php artisan db:seed --class=PlansEnableAllBillingTypesSeeder
 *
 * Idempotente: só altera planos que ainda não têm allowed_billing_types
 * configurado, preservando overrides feitos manualmente.
 */
class PlansEnableAllBillingTypesSeeder extends Seeder
{
    public function run(): void
    {
        $all = ['CREDIT_CARD', 'PIX', 'BOLETO'];

        Plan::query()
            ->whereNull('allowed_billing_types')
            ->orderBy('id')
            ->each(function (Plan $plan) use ($all) {
                $plan->allowed_billing_types = $all;
                $plan->save();
            });
    }
}
