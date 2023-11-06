<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Voting;
use Illuminate\Http\Request;

final class OmittedController extends Controller
{
    public function removeVoting(int $votingId, Request $request): void
    {
        $omittedData = (object) $request['omitted'];
        $voting = Voting::where('id', $votingId)
            ->where('omitted_id', $omittedData->id);
        if ($voting) {
            foreach (Answer::where('omitted_id', $omittedData->id)->where('voting_id', $votingId)->get() as $answer) {
                $answer->forceDelete();
            }
            $voting->forceDelete();
        }
    }
}
