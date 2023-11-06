<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFund extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string[]
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'count_pif',
        'user_id',
        'fund_id',
    ];

    public function fund(): HasOne
    {
        return $this->hasOne(Fund::class, 'id', 'fund_id');
    }

    public function scha(): float
    {
        return $this->fund->current_count_pif * $this->fund->current_cost_one_pif;
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function port_cost(): float
    {
        return $this->count_pif * $this->fund->current_cost_one_pif;
    }
}
