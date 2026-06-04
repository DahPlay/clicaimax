<?php

namespace App\Models;

use App\Enums\CycleAsaasEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'value',
        'description',
        'is_active',
        'is_best_seller',
        'cycle',
        'billing_type',
        'allowed_billing_types',
        'free_for_days',
        'priority',
        'hidden',
        'is_active_telemedicine',
    ];

    protected $casts = [
        'allowed_billing_types' => 'array',
    ];

    protected function value(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => str_replace(['.', ','], ['', '.'], $value),
        );
    }

    /**
     * Lista efetiva de billing types aceitos: usa o JSON quando preenchido,
     * fallback ao billing_type singular legado quando vazio/null.
     */
    public function getEffectiveBillingTypesAttribute(): array
    {
        $list = $this->allowed_billing_types;
        if (is_array($list) && count($list) > 0) {
            return array_values(array_filter($list));
        }
        return $this->billing_type ? [$this->billing_type] : ['CREDIT_CARD'];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(PlanBenefit::class);
    }

    public function packagePlans(): HasMany
    {
        return $this->hasMany(PackagePlan::class);
    }

    public static function getPlansData(?int $exceptId = null): array
    {
        $plans = Plan::query()
            ->where('is_active', 1)
            ->when($exceptId, function ($query, $exceptId) {
                return $query->where('id', '!=', $exceptId);
            })
            ->orderBy('value')
            ->get();

        $plansByCycle = $plans->groupBy('cycle');
        $cycles = $plansByCycle->keys()->mapWithKeys(fn($cycle) => [
            $cycle => CycleAsaasEnum::from($cycle)->getName()
        ]);

        $activeCycle = $plans->firstWhere('is_best_seller', true)?->cycle ?? $plansByCycle->keys()->first();

        return [
            'cycles' => $cycles,
            'plansByCycle' => $plansByCycle,
            'activeCycle' => $activeCycle,
        ];
    }
}
