<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as App;

/**
 *
 */
final class NewsController extends Controller
{

    /**
     * @return Application|Factory|View|App
     */
    public function all(): Factory|View|App|Application
    {
        $news = News::all();
        $title = 'Новости для владельцев инвестиционных паев';
        $route = 'news';
        return view('front.news', compact('news', 'title', 'route'));
    }

    /**
     * @param string $alias
     * @return Application|Factory|View|App
     */
    public function getPost(string $alias): Factory|View|App|Application
    {
        $post = News::where('alias', $alias)->first();
        $title = $post->title;
        return view('front.post', compact('post', 'title'));
    }
}
