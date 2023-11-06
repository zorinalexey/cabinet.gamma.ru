<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Services\Bulletin;
use App\Http\Services\DocumentService;
use App\Http\Services\OmittedService;
use App\Models\Omitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VotingController extends Controller
{
    public function view($id)
    {
        $user = Auth::user();
        $omitted = Omitted::find($id);
        $fund = false;
        foreach ($user->funds as $item) {
            if ($item->id === $omitted->fund->id) {
                $fund = $item;
                break;
            }
        }
        $paper_ballot_link = Bulletin::getBulletenUserByOmitted($user, $omitted);
        $bladeVars = [
            'omitted' => $omitted,
            'user' => $user,
            'fund' => $fund,
            'paper_ballot_link' => $paper_ballot_link,
            'vote' => OmittedService::getVote($user, $omitted)
        ];
        return view('front.voting', $bladeVars);
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'omitted' => ['required'],
            '_token' => ['required'],
            'answer' => ['required']
        ]);
        $omitted = Omitted::find($data['omitted']);
        $user = Auth::user();
        $fund = false;
        foreach ($user->funds as $item) {
            if ($item->id === $omitted->fund->id) {
                $fund = $item;
                break;
            }
        }
        OmittedService::saveVoting($request, $user, $omitted);
        $paper_ballot_link = DocumentService::createBlankBulletin($user, $omitted, true);
        $bladeVars = [
            'omitted' => $omitted,
            'user' => $user,
            'fund' => $fund,
            'paper_ballot_link' => $paper_ballot_link,
            'vote' => OmittedService::getVote($user, $omitted)
        ];
        return view('front.voting', $bladeVars);
    }
}
