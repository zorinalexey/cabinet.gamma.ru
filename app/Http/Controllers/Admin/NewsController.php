<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\NewsService;
use App\Models\News;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class NewsController extends Controller
{
    /**
     * Просмотр списка
     */
    public function index(): View|Application|Factory|App
    {
        return view('admin.news.list', [
            'active_news' => News::paginate(25),
            'delete_news' => News::onlyTrashed()->paginate(25),
        ]);
    }

    /**
     * Создать
     */
    public function create(): View|Application|Factory|App
    {
        return view('admin.news.create');
    }

    /**
     * Сохранить
     */
    public function store(Request $request): RedirectResponse
    {
        $data = NewsService::post($request);
        $post = new News();
        foreach ($data as $key => $value) {
            $post->$key = $value;
        }
        $post->save();

        return redirect(route('admin.post.list'));
    }

    /**
     * Редактировать
     */
    public function edit(string $id): View|Application|Factory|App
    {
        $post = News::find($id);

        return view('admin.news.edit', compact('post'));
    }

    /**
     * Сохранить изменения
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $post = News::find($id);
        $data = NewsService::post($request);
        foreach ($data as $key => $value) {
            $post->$key = $value;
        }
        $post->save();

        return redirect(route('admin.post.list'));
    }

    /**
     * Мягкое удаление
     */
    public function destroy(string $id): RedirectResponse
    {
        $post = News::find($id);
        $post->delete();

        return redirect(route('admin.post.list'));
    }

    /**
     * Полное удаление
     */
    public function delete(string $id): RedirectResponse
    {
        $post = News::withTrashed()->find($id);
        $post->forceDelete();

        return redirect(route('admin.post.list'));
    }

    /**
     * Восстановить запись
     */
    public function restoreModel(int $id): RedirectResponse
    {
        News::withTrashed()->where('id', $id)->restore();

        return redirect()->back();
    }
}
