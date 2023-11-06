<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fund extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'qualification_text',
        'qualification_value',
        'access_users',
        'status',
        'current_count_pif',
        'current_cost_one_pif',
        'rules',
        'policy',
        'destiny',
        'desc',
        'omitted_min_percent',
    ];

    /**
     * Расчет роста фонда в процентах
     */
    public function growth_calculation_percent(): float
    {
        $current_cost = $this->current_count_pif * $this->current_cost_one_pif;
        $last_cost = $this->last_count_pif * $this->last_cost_one_pif;
        if (! $last_cost) {
            return 100;
        }
        $percent = $last_cost / 100;

        return round(($current_cost - $last_cost) / $percent, 2);
    }

    public function users()
    {
        return $this->hasMany(UserFund::class, 'fund_id', 'id');
    }

    /**
     * Расчет роста фонда в натуральных единицах
     */
    public function growth_calculation_sum(): float
    {
        $current_cost = $this->current_count_pif * $this->current_cost_one_pif;
        $last_cost = $this->last_count_pif * $this->last_cost_one_pif;
        $sum = $last_cost - $current_cost;

        return -$sum;
    }

    /**
     * Общая стоимость всех активов фонда
     */
    public function cost(): float
    {
        return $this->current_count_pif * $this->current_cost_one_pif;
    }

    /**
     * Проверка доступности фонда для определенного пользователя
     */
    public function access(User $user): bool
    {
        return $this->access_users && in_array((string) $user->id, json_decode($this->access_users, true), true);
    }
}
