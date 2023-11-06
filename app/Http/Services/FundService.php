<?php

namespace App\Http\Services;

use App\Models\Fund;
use App\Models\User;
use Illuminate\Http\Request;

class FundService
{
    /**
     * Доступные фонды юзера
     */
    public static function getUserAccessFunds(?User $user): array
    {
        $all_funds = Fund::all();
        $qual_user_funds = [];
        $no_qual_user_funds = [];
        $all_user_funds = [];
        foreach ($all_funds as $fund) {
            if ($user->qualification_value >= $fund->qualification_value && $fund->qualification_value === 1 && ! $fund->access($user)) {
                $all_user_funds[] = $fund;
                $no_qual_user_funds[] = $fund;
            } elseif ($user->qualification_value >= $fund->qualification_value && $fund->qualification_value === 2 && ! $fund->access($user)) {
                $all_user_funds[] = $fund;
                $qual_user_funds[] = $fund;
            } elseif ($fund->access($user)) {
                $all_user_funds[] = $fund;
                if ($fund->qualification_value === 2) {
                    $qual_user_funds[] = $fund;
                } elseif ($fund->qualification_value === 1) {
                    $no_qual_user_funds[] = $fund;
                }
            }
        }
        $funds['all_user_funds'] = $all_user_funds;
        $funds['no_qual_user_funds'] = $no_qual_user_funds;
        $funds['qual_user_funds'] = $qual_user_funds;

        return $funds;
    }

    public static function getCreateFund(Request $request): array
    {
        $data = $request->validate([
            '_token' => ['required'],
            'name' => ['required'],
            'qualification_text' => ['required'],
            'qualification_value' => ['required'],
            'access_users' => [],
            'status' => ['required'],
            'current_count_pif' => ['required'],
            'current_cost_one_pif' => ['required'],
            'rules' => ['required'],
            'policy' => ['required'],
            'destiny' => ['required'],
            'desc' => ['required'],
            'omitted_min_percent' => ['required'],
        ]);
        unset($data['_token']);
        if (isset($data['access_users'])) {
            $data['access_users'] = json_encode($data['access_users'], JSON_THROW_ON_ERROR);
        } else {
            $data['access_users'] = '';
        }

        return $data;
    }

    public static function getEditFund(Request $request): array
    {
        $data = $request->validate([
            '_token' => ['required'],
            'name' => ['required'],
            'qualification_text' => ['required'],
            'qualification_value' => ['required'],
            'access_users' => [],
            'status' => ['required'],
            'current_count_pif' => ['required'],
            'current_cost_one_pif' => ['required'],
            'last_count_pif',
            'last_cost_one_pif',
            'rules' => ['required'],
            'policy' => ['required'],
            'destiny' => ['required'],
            'desc' => ['required'],
            'omitted_min_percent' => ['required'],
        ]);
        unset($data['_token']);
        if (isset($data['access_users'])) {
            $data['access_users'] = json_encode($data['access_users'], JSON_THROW_ON_ERROR);
        } else {
            $data['access_users'] = '';
        }

        return $data;
    }
}
