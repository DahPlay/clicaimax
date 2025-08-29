<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('credit_card_number', 4)->nullable()->after('mobile');
            $table->string('credit_card_brand', 20)->nullable()->after('credit_card_number');
            $table->string('credit_card_token', 36)->nullable()->after('credit_card_brand');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('credit_card_number');
            $table->dropColumn('credit_card_brand');
            $table->dropColumn('credit_card_token');
        });
    }
};
