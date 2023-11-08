<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Voting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    /**
     * Ответы на вопрос голосования
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'voting_id', 'id');
    }

    public function getVotesAgainst():int
    {
        $answers = Answer::query()->where('omitted_id', $this->omitted_id)
            ->where('voting_id', $this->id)
            ->where('answer', '=', 0)
            ->get();
        return count($answers);
    }

    public function getVotesFor():int
    {
        $answers = Answer::query()->where('omitted_id', $this->omitted_id)
            ->where('voting_id', $this->id)
            ->where('answer', '>', 0)
            ->get();
        return count($answers);
    }

    /**
     * Фонд голосования
     */
    public function fund(): HasOne
    {
        return $this->hasOne(Fund::class, 'id', 'fund_id');
    }

    /**
     * Голосование к которому относится вопрос
     */
    public function omitted(): HasOne
    {
        return $this->hasOne(Omitted::class, 'id', 'omitted_id');
    }
}
