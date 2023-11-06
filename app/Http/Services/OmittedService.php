<?php

namespace App\Http\Services;

use App\Models\Answer;
use App\Models\Omitted;
use App\Models\OmittedDocument;
use App\Models\UserDocument;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use RuntimeException;

class OmittedService
{
    public static function saveVoting(Request $request, Authenticatable|null $user, Omitted $omitted): void
    {
        $data = $request->validate([
            'omitted' => ['required'],
            '_token' => ['required'],
            'answer' => ['required']
        ]);
        foreach ($data['answer'] as $voting_id => $value) {
            $answer = Answer::where('omitted_id', $omitted->id)
                ->where('user_id', $user->id)
                ->where('voting_id', $voting_id)
                ->first();
            if (!$answer) {
                $answer = new Answer();
                $answer->omitted_id = $omitted->id;
                $answer->user_id = $user->id;
                $answer->voting_id = $voting_id;
            }
            if ($value === 'За') {
                $answer->answer = true;
            } else {
                $answer->answer = false;
            }
            $answer->save();
        }
    }

    public static function getVote(Authenticatable|null $user, Omitted $omitted): bool
    {
        $check_sign = false;
        $check_date = false;
        $check_answers = false;
        if (strtotime($omitted->end_date) > time()) {
            $check_date = true;
        }
        $answers = Answer::where('omitted_id', $omitted->id)
            ->where('user_id', $user->id)
            ->first();
        if ($answers) {
            $check_answers = true;
        }
        $sign_doc = UserDocument::where('search_hash', $user->id . '_omitted_' . $omitted->id)->first();
        if ($sign_doc && $sign_doc->sign_status) {
            $check_sign = true;
        }
        return $check_answers && $check_date && $check_sign;
    }

    public static function checkAnswer(int $user_id, int $omitted_id, int $voting_id): Answer|null
    {
        return Answer::where('omitted_id', $omitted_id)
            ->where('user_id', $user_id)
            ->where('voting_id', $voting_id)
            ->first();
    }

    public static function omitted(Request $request, Omitted|null $omitted = null): array
    {
        $data = $request->validate([
            '_token' => ['required'],
            'name' => ['required'],
            'fund_id' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'total_date' => ['required'],
            'file' => ['required']
        ]);
        unset($data['_token']);
        $data['start_date'] = date('Y-m-d H:i:s', strtotime($data['start_date']));
        $data['end_date'] = date('Y-m-d H:i:s', strtotime($data['end_date']));
        $data['total_date'] = date('Y-m-d H:i:s', strtotime($data['total_date']));
        if ($data['file'] && $omitted) {
            $path = config('company_details')['root_catalog'] . '/storage/app/omitteds/' . $omitted->id . '/' . $_FILES['file']['full_path'];
            $dir = dirname($path);
            if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
            if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
                $omittedFile = OmittedDocument::where('omitted_id', $omitted->id)->first();
                if (!$omittedFile) {
                    $omittedFile = new OmittedDocument();
                    $omittedFile->omitted_id = $omitted->id;
                }
                $omittedFile->link = $path;
                $omittedFile->name = 'Вложение к голосованию';
                $omittedFile->save();
            }
        }
        unset($data['file']);
        return $data;
    }

    public static function votings(Request $request): array
    {
        $data = $request->validate([
            '_token' => ['required'],
            'votings' => ['required'],
        ]);
        unset($data['_token']);
        return $data['votings'];
    }
}
