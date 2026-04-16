<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerDependent extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'birth_date',
        'email',
        'cpf',
        'gender',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected function cpf(): Attribute
    {
        return Attribute::make(
            set: fn(?string $value) => $value !== null ? sanitize($value) : null,
        );
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
