<?php

namespace App\Models;

use App\Http\Services\NotificationService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 *
 */
final class User extends AuthUser
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'esia_id',
        'email',
        'code',
        'role',
        'lastname',
        'name',
        'patronymic',
        'gender',
        'qualification_text',
        'qualification_value',
        'birth_date',
        'birth_place',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Все фонды пользователя
     * @return HasMany
     */
    public function funds(): HasMany
    {
        return $this->hasMany(UserFund::class);
    }

    /**
     * Голосования пользователя
     * @return HasMany
     */
    public function omitteds(): HasMany
    {
        return $this->hasMany(UserOmitted::class, 'user_id', 'id');
    }

    /**
     * Ответы пользователя на голосованиях
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'user_id', 'id');
    }

    /**
     * Стоимость всех паев всех фондов у данного пользователя
     * @return float
     */
    public function all_cost_pif(): float
    {
        $sum = 0;
        foreach ($this->funds as $fund) {
            $sum += $fund->count_pif * $fund->fund->current_cost_one_pif;
        }
        return $sum;
    }

    /**
     * Оповещения пользователя
     * @return array
     */
    public function notifications(): array
    {
        return NotificationService::get();
    }

    /**
     * Паспорт пользователя
     * @return HasOne
     */
    public function passport(): HasOne
    {
        return $this->hasOne(UserPassport::class, 'user_id', 'id');
    }

    /**
     * Количество всех паев фонда пользователя
     * @param Fund $fund
     * @return float
     */
    public function countPifUserByFund(Fund $fund): float
    {
        foreach ($this->funds as $item) {
            if ($item->fund_id === $fund->id) {
                return $item->count_pif;
            }
        }
        return 0;
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class, 'user_id', 'id');
    }

    public function getNoSignDocuments(): array
    {
        $docs = [];
        foreach ($this->documents as $document) {
            if ($document->is_sign && !$document->sign_status) {
                $docs[] = $document;
            }
        }
        if (count($docs) > 0) {
            NotificationService::set('У Вас есть ' . count($docs) . ' не подписанных документов', 'doc');
        }
        $this->notice();
        return $docs;
    }

    private function notice(): void
    {
        if (!$this->inn) {
            NotificationService::set('У Вас не указан ИНН ', 'inn_account');
        }
        if (!$this->snils) {
            NotificationService::set('У Вас не указан СНИЛС ', 'snils_account');
        }
        if ($this->ruble_accounts->count() === 0) {
            NotificationService::set('У Вас не указан ни один рублевый счет', 'ruble_account');
        }
        if ($this->currency_accounts->count() === 0) {
            #NotificationService::set('У Вас не указан ни один валютный счет', 'currency_account');
        }
        if (!$this->address_registration()) {
            NotificationService::set('У Вас не указан адрес регистрации', 'reg_addr');
        }
        if (!$this->address_fact()) {
            NotificationService::set('У Вас не указан адрес проживания', 'fact_addr');
        }
    }

    public function address_registration(): UserAddress|null
    {
        return UserAddress::where('user_id', $this->id)
            ->where('address_status', 0)->first();
    }

    public function address_fact(): UserAddress|null
    {
        return UserAddress::where('user_id', $this->id)
            ->where('address_status', 1)->first();
    }

    public function ruble_accounts(): HasMany
    {
        return $this->hasMany(UserRubleAccount::class, 'user_id', 'id');
    }

    public function currency_accounts(): HasMany
    {
        return $this->hasMany(UserCurrencyAccount::class, 'user_id', 'id');
    }

    public function inn(): HasOne
    {
        return $this->hasOne(UserInn::class, 'user_id', 'id');
    }

    public function snils(): HasOne
    {
        return $this->hasOne(UserSnils::class, 'user_id', 'id');
    }

    public function getOmittedDocument(Omitted $omitted): UserDocument|null
    {
        $searchString = $this->id . '_omitted_' . $omitted->id;
        return UserDocument::where('search_hash', $searchString)->first();
    }

    public function answersOmitted(Omitted $omitted): Collection
    {
        return Answer::where('omitted_id', $omitted->id)
            ->where('user_id', $this->id)
            ->get();
    }

    public function check(): array
    {
        $data['passport'] = $this->checkPassport();
        $data['fromu'] = $this->checkFromu();
        $data['fedsfm'] = $this->checkFedSfm();
        $data['mvk'] = $this->checkMvk();
        $data['p639'] = $this->checkP639();
        return $data;
    }

    /**
     * @return CheckUserPassport|null
     */
    private function checkPassport(): CheckUserPassport|null
    {
        $passport = $this->passport;
        $check = CheckUserPassport::where('user_id', $this->id)->first();
        if (!$check) {
            $check = new CheckUserPassport();
        }
        $check->user_id = $this->id;
        $check->check = false;
        if (NotValidPassport::where('series', $passport->series)->where('number', $passport->number)->first()) {
            $check->check = true;
        }
        $check->save();
        return CheckUserPassport::where('user_id', $this->id)->first();
    }

    /**
     * @return CheckerFromu|null
     */
    private function checkFromu(): CheckerFromu|null
    {
        $check = CheckerFromu::where('user_id', $this->id)->first();
        if (!$check) {
            $check = new CheckerFromu();
        }
        $check->user_id = $this->id;
        $check->check = '';
        $fio = mb_strtoupper(trim($this->lastname . ' ' . $this->name . ' ' . $this->patronymic));
        if ($checker = FromuBase::where('data', $fio)->first()) {
            $check->check = $checker->remark;
        }
        $check->save();
        return CheckerFromu::where('user_id', $this->id)->first();
    }

    /**
     * @return CheckerFedSfm|null
     */
    private function checkFedSfm(): CheckerFedSfm|null
    {
        $check = CheckerFedSfm::where('user_id', $this->id)->first();
        if (!$check) {
            $check = new CheckerFedSfm();
        }
        $check->user_id = $this->id;
        $check->check = null;
        $fio = mb_strtoupper($this->lastname . ' ' . $this->name . ' ' . $this->patronymic);
        $regAddr = $this->address_registration()->address ?? null;
        $factAddr = $this->address_fact()->address ?? null;
        $checker = FedSfmBase::where('data', 'LIKE', '%' . $fio . '%' . $this->passport->series . ' ' . $this->passport->number . '%')
            ->orWhere('data', 'LIKE', '%' . $fio . '%' . $regAddr . '%')
            ->orWhere('data', 'LIKE', '%' . $fio . '%' . $factAddr . '%')
            ->orWhere('data', 'LIKE', '%' . $fio . '%' . $this->passport->series . ' ' . $this->passport->number . '%' . $regAddr . '%')
            ->orWhere('data', 'LIKE', '%' . $fio . '%' . $this->passport->series . ' ' . $this->passport->number . '%' . $factAddr . '%')
            ->first();
        if ($checker) {
            $check->check = $checker->remark;
        }
        $check->save();
        return CheckerFedSfm::where('user_id', $this->id)->first();
    }

    /**
     * @return CheckerMvk|null
     */
    private function checkMvk(): CheckerMvk|null
    {
        $check = CheckerMvk::where('user_id', $this->id)->first();
        if (!$check) {
            $check = new CheckerMvk();
        }
        $check->user_id = $this->id;
        $check->check = null;
        $data = $this->lastname . ' ' . $this->name . ' ' . $this->patronymic . ' ';
        $data .= date('d.m.Y', strtotime($this->birth_date)) . ' ';
        $data .= $this->birth_place;
        if ($checker = MvkBase::where('data', mb_strtoupper($data))->first()) {
            $check->check = $checker->remark;
        }
        $check->save();
        return CheckerMvk::where('user_id', $this->id)->first();
    }

    /**
     * @return CheckerP639|null
     */
    private function checkP639(): CheckerP639|null
    {
        $check = CheckerP639::where('user_id', $this->id)->first();
        if (!$check) {
            $check = new CheckerP639();
        }
        $check->user_id = $this->id;
        $check->check = null;
        $data = $this->lastname . ' ' . $this->name . ' ' . $this->patronymic . ' ';
        $data .= date('d.m.Y', strtotime($this->birth_date)) . ' ';
        $data .= $this->passport->series . ' ' . $this->passport->number;
        if ($checker = P639Base::where('data', mb_strtoupper($data))->first()) {
            $check->check = $checker->remark;
        }
        $check->save();
        return CheckerP639::where('user_id', $this->id)->first();
    }

}
