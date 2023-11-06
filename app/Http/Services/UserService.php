<?php

namespace App\Http\Services;

use App\Models\Fund;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserFund;
use App\Models\UserInn;
use App\Models\UserPassport;
use App\Models\UserSnils;
use Illuminate\Http\Request;

class UserService
{
    public static function user(Request $request): array
    {
        $data = $request->validate([
            '_token' => ['required'],
            'lastname' => ['required'],
            'name' => ['required'],
            'patronymic' => [],
            'birth_date' => ['required'],
            'gender' => ['required'],
            'birth_place' => ['required'],
            'role' => ['required'],
            'qualification_text' => ['required'],
            'qualification_value' => ['required'],
            'phone' => ['required'],
            'email' => ['required'],
        ]);
        $data['birth_date'] = date('Y-m-d H:i:s', strtotime($data['birth_date']));
        unset($data['_token']);

        return $data;
    }

    public static function saveUser(Request $request, User $user): void
    {
        $passportData = self::passport($request);
        $passportData['user_id'] = $user->id;
        $passport = UserPassport::where('user_id', $user->id)->first();
        if (! $passport) {
            UserPassport::create($passportData);
        } else {
            $passport->update($passportData);
        }
        UserSnils::updateOrCreate(
            ['number' => self::snils($request), 'user_id' => $user->id],
            ['user_id' => $user->id]
        );
        UserInn::updateOrCreate(
            ['number' => self::inn($request), 'user_id' => $user->id],
            ['user_id' => $user->id]
        );
        UserAddress::updateOrCreate(
            ['address' => self::addrReg($request), 'user_id' => $user->id, 'address_status' => 1],
            ['user_id' => $user->id]
        );
        UserAddress::updateOrCreate(
            ['address' => self::addrFact($request), 'user_id' => $user->id, 'address_status' => 0],
            ['user_id' => $user->id]
        );
    }

    public static function passport(Request $request): array
    {
        $data = $request->validate([
            '_token' => ['required'],
            'series' => ['required'],
            'number' => ['required'],
            'when_issued' => ['required'],
            'division_code' => ['required'],
            'issued_by' => ['required'],
        ]);
        $data['when_issued'] = date('Y-m-d H:i:s', strtotime($data['when_issued']));
        unset($data['_token']);

        return $data;
    }

    public static function snils(Request $request): string
    {
        $data = $request->validate([
            '_token' => ['required'],
            'snils' => ['required'],
        ]);

        return $data['snils'];
    }

    public static function inn(Request $request): string
    {
        $data = $request->validate([
            '_token' => ['required'],
            'inn' => ['required'],
        ]);

        return $data['inn'];
    }

    public static function addrReg(Request $request): string
    {
        $data = $request->validate([
            '_token' => ['required'],
            'reg_addr' => ['required'],
        ]);

        return $data['reg_addr'];
    }

    public static function addrFact(Request $request): string
    {
        $data = $request->validate([
            '_token' => ['required'],
            'fact_addr' => ['required'],
        ]);

        return $data['fact_addr'];
    }

    public static function getOmittebAccess(UserFund $userFund, Fund $fund): bool
    {
        $percent = round($userFund->count_pif / $fund->current_count_pif * 100, 2);

        return $percent > $fund->omitted_min_percent;
    }
}
