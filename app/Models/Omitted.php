<?php

namespace App\Models;

use App\Http\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Omitted extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'name',
        'fund_id',
        'is_public',
        'start_date',
        'end_date',
        'total_date',
    ];

    /**
     * Фонд голосования
     */
    public function fund(): HasOne
    {
        return $this->hasOne(Fund::class, 'id', 'fund_id');
    }

    /**
     * Документы голосования
     */
    public function documents(): HasOne
    {
        return $this->hasOne(OmittedDocument::class, 'omitted_id', 'id');
    }

    /**
     * Вопросы голосования
     */
    public function votings(): HasMany
    {
        return $this->hasMany(Voting::class, 'omitted_id', 'id');
    }

    /**
     * Ответы голосования
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'omitted_id', 'id');
    }

    /**
     * Ответы пользователя на голосование
     */
    public function userAnswers(Voting $voting, User $user): array
    {
        $user_answers = [];
        foreach ($voting->answers as $answer) {
            if ($answer->user_id === $user->id) {
                $user_answers[] = $answer;
            }
        }

        return $user_answers;
    }

    public function status(): string
    {
        $is_public = false;
        $start_date = false;
        $end_date = false;
        if ($this->is_public) {
            $is_public = true;
        }
        if (strtotime($this->start_date) < time()) {
            $start_date = true;
        }
        if (strtotime($this->end_date) > time()) {
            $end_date = true;
        }
        if ($is_public && $start_date && $end_date) {
            return 'Открыт';
        }

        return 'Закрыт';
    }

    /**
     * @return int[]
     */
    public function getAnswersPercent(Collection $answers): array
    {
        $cuntVoitings = count($this->votings);
        $data = ['true' => 0, 'false' => 0];
        foreach ($answers as $answer) {
            if ($answer->answer) {
                $data['true']++;
            } else {
                $data['false']++;
            }
        }
        if ($data['true']) {
            $data['true'] = round($data['true'] / $cuntVoitings * 100, 2);
        }
        if ($data['false']) {
            $data['false'] = round($data['false'] / $cuntVoitings * 100, 2);
        }

        return $data;
    }

    public function getUsers(): array
    {
        $fund = Fund::find($this->fund_id);
        $users = [];
        foreach ($fund->users as $item) {
            $user = User::find($item->user_id);
            if (UserService::getOmittebAccess($item, $fund)) {
                $users[] = $user;
            }
        }

        return $users;
    }
}
