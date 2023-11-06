<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Services\FundService;
use App\Http\Services\QrCodePayment;
use App\Models\Fund;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
final class FundController extends Controller
{

    /**
     * Отобразить все фонды
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function all(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $funds = FundService::getUserAccessFunds(Auth::user());
        return view('front.funds', $funds);
    }

    /**
     * Отобразить фонд по его id
     * @param int $fundId
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function viewFund(int $fundId): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $user = Auth::user();
        $fund = Fund::where('id', $fundId)->first();
        $company_details = config('company_details');
        $qrCode = QrCodePayment::getQrCode($user);
        return view('front.single_fund', compact('fund', 'user', 'company_details', 'qrCode'));
    }

}
