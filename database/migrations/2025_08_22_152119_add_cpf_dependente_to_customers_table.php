<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('cpf_dependente_1')->nullable();
            $table->string('cpf_dependente_2')->nullable();
            $table->string('cpf_dependente_3')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('cpf_dependente_1');
            $table->dropColumn('cpf_dependente_2');
            $table->dropColumn('cpf_dependente_3');
        });
    }
};
