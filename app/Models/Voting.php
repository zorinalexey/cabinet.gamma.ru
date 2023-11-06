<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
final class Voting extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'omitted_id',
        'fund_id',
        'other_conditions',
        'type_transaction',
        'parties_transaction',
        'subject_transaction',
        'cost_transaction',
    ];

    /**
     * Ответы на вопрос голосования
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'voting_id', 'id');
    }

    /**
     * Фонд голосования
     * @return HasOne
     */
    public function fund(): HasOne
    {
        return $this->hasOne(Fund::class, 'id', 'fund_id');
    }

    /**
     * Голосование к которому относится вопрос
     * @return HasOne
     */
    public function omitted(): HasOne
    {
        return $this->hasOne(Omitted::class, 'id', 'omitted_id');
    }
}
