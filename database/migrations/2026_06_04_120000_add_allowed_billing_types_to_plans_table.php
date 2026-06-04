<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            // JSON nullable: lista de billing types aceitos pelo plano.
            // Fallback: quando null, o sistema usa $plan->billing_type singular legado.
            $table->json('allowed_billing_types')->nullable()->after('billing_type');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('allowed_billing_types');
        });
    }
};
