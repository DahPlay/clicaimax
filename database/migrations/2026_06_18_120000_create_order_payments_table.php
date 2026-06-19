<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('payment_asaas_id')->unique();
            $table->string('subscription_asaas_id')->nullable()->index();
            $table->string('customer_asaas_id')->nullable();

            $table->string('billing_type')->nullable();
            $table->string('status')->index();
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('net_value', 10, 2)->nullable();

            $table->date('due_date')->index();
            $table->date('original_due_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->date('client_payment_date')->nullable();

            $table->string('invoice_url')->nullable();
            $table->string('bank_slip_url')->nullable();
            $table->string('transaction_receipt_url')->nullable();
            $table->string('invoice_number')->nullable();

            $table->json('raw')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
