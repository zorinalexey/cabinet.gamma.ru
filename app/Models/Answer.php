<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Answer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'omitted_id',
        'voting_id',
        'user_id',
        'answer',
    ];

    /**
     * Пользователь к которому относится ответ
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Вопрос к которому относится ответ
     */
    public function voting(): HasOne
    {
        return $this->hasOne(Voting::class, 'id', 'voting_id');
    }

    /**
     * Голосование к которому относится ответ
     */
    public function omitted(): HasOne
    {
        return $this->hasOne(Omitted::class, 'id', 'omitted_id');
    }
}
