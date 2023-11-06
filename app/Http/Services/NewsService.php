<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsService
{
    public static function post(Request $request)
    {
        $data = $request->validate([
            '_token' => ['required'],
            'title' => ['required'],
            'content' => ['required']
        ]);
        unset($data['_token']);
        $data['alias'] = Str::slug($data['title'], '_');
        return $data;
    }
}
