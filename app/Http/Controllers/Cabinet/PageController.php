<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;

class PageController extends Controller
{

    public function getPage(string $alias)
    {
        $content = StaticPage::where('alias', $alias)->first();
        if (!$content) {
            return abort(404);
        }

        return view('front.page', compact('content'));
    }

    public function list()
    {
        $news = StaticPage::all();
        $title = 'Ресурсы';
        $route = 'pages';
        return view('front.news', compact('news', 'title', 'route'));
    }
}
