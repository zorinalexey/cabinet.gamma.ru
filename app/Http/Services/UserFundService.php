<?php

namespace App\Http\Services;

use Illuminate\Http\Request;

class UserFundService
{
    public static function fundInfo(Request $request): array
    {
        $data = $request->validate([
            '_token' => ['required'],
            'user_id' => ['required'],
            'fund_id' => ['required'],
            'count_pif' => ['required'],
        ]);
        unset($data['_token']);
        return $data;
    }
}
