<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsCreateRequest;
use App\Http\Requests\NewsUpdateRequest;
use App\Http\Services\NewsService;
use App\Models\News;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    public function store(NewsCreateRequest $request): RedirectResponse
    {

        $data = $request->validated();
        $data['alias'] = Str::slug($data['title'], '_');

        if(News::query()->create($data)){
            return redirect(route('admin.post.list'));
        }

        abort(500);
    }

    /**
     * Редактировать
     */
    public function edit(News $news): View|Application|Factory|App
    {
        $post = $news;

        return view('admin.news.edit', compact('post'));
    }

    /**
     * Сохранить изменения
     */
    public function update(NewsUpdateRequest $request, News $news): RedirectResponse
    {
        $data = $request->validated();
        $data['alias'] = Str::slug($data['title'], '_');

        if($news->update($data)){
            return redirect(route('admin.post.list'));
        }

        abort(500);
    }

    /**
     * Мягкое удаление
     */
    public function destroy(News $news): RedirectResponse
    {
        if($news->delete()){
            return redirect(url()->previous());
        }

        abort(500);
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
