<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

final class UserDocument extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getAddModels(): array
    {
        $data = [
            'account' => null,
            'fund' => null,
            'user' => $this->user,
            'omitted' => null,
        ];
        if (preg_match('~(.+)_account_(?<account>\d+)_(.+)~', $this->search_hash, $matches)) {
            $data['account'] = UserRubleAccount::find($matches['account']);
        }
        if (preg_match('~(.+)_fund_(?<fund>\d+)~', $this->search_hash, $matches)) {
            $data['fund'] = Fund::find($matches['fund']);
        }
        if (preg_match('~(.+)_omitted_(?<omitted>\d+)~', $this->search_hash, $matches)) {
            $data['omitted'] = Omitted::find($matches['omitted']);
            if($data['omitted']){
                $data['omitted']->status = $data['omitted']?->status()?:null;
            }
        }
        return $data;
    }

    public function getStatus(): ?string
    {
        if ($this->sign_status && $this->is_sign) {
            return 'Документ подписан';
        }

        if (! $this->sign_status && $this->is_sign) {
            return 'Документ не подписан';
        }

        return null;
    }
}
