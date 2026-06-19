<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_asaas_id',
        'subscription_asaas_id',
        'customer_asaas_id',
        'billing_type',
        'status',
        'value',
        'net_value',
        'due_date',
        'original_due_date',
        'payment_date',
        'client_payment_date',
        'invoice_url',
        'bank_slip_url',
        'transaction_receipt_url',
        'invoice_number',
        'raw',
    ];

    protected $casts = [
        'due_date'            => 'date',
        'original_due_date'   => 'date',
        'payment_date'        => 'date',
        'client_payment_date' => 'date',
        'value'               => 'decimal:2',
        'net_value'           => 'decimal:2',
        'raw'                 => 'array',
    ];

    public const OPEN_STATUSES = ['PENDING', 'AWAITING_RISK_ANALYSIS', 'OVERDUE'];
    public const PAID_STATUSES = ['RECEIVED', 'RECEIVED_IN_CASH', 'CONFIRMED'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function upsertFromAsaas(int $orderId, array $payment): self
    {
        $attrs = [
            'order_id'                => $orderId,
            'subscription_asaas_id'   => $payment['subscription']     ?? null,
            'customer_asaas_id'       => $payment['customer']         ?? null,
            'billing_type'            => $payment['billingType']      ?? null,
            'status'                  => $payment['status']           ?? 'PENDING',
            'value'                   => $payment['value']            ?? 0,
            'net_value'               => $payment['netValue']         ?? null,
            'due_date'                => $payment['dueDate']          ?? null,
            'original_due_date'       => $payment['originalDueDate']  ?? null,
            'payment_date'            => $payment['paymentDate']      ?? null,
            'client_payment_date'     => $payment['clientPaymentDate']?? null,
            'invoice_url'             => $payment['invoiceUrl']       ?? null,
            'bank_slip_url'           => $payment['bankSlipUrl']      ?? null,
            'transaction_receipt_url' => $payment['transactionReceiptUrl'] ?? null,
            'invoice_number'          => $payment['invoiceNumber']    ?? null,
            'raw'                     => $payment,
        ];

        return static::updateOrCreate(
            ['payment_asaas_id' => $payment['id']],
            $attrs,
        );
    }
}
