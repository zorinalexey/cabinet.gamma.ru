<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaticPageCreateRequest;
use App\Http\Requests\StaticPageUpdateRequest;
use App\Models\StaticPage;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class PagesController extends Controller
{
    /**
     * Просмотр списка
     */
    public function index(): View|Application|Factory|App
    {
        return view('admin.pages.list', [
            'active_pages' => StaticPage::paginate(25),
            'delete_pages' => StaticPage::onlyTrashed()->paginate(25),
        ]);
    }

    /**
     * Создать
     */
    public function create(): View|Application|Factory|App
    {
        return view('admin.pages.create');
    }

    /**
     * Сохранить
     */
    public function store(StaticPageCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['alias'] = Str::slug($data['title'], '_');

        if(StaticPage::query()->create($data)){
            return redirect(route('admin.page.list'));
        }

        abort(500);
    }

    /**
     * Редактировать
     */
    public function edit(StaticPage $staticPage): View|Application|Factory|App
    {
        $page = $staticPage;

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Сохранить изменения
     */
    public function update(StaticPageUpdateRequest $request, StaticPage $staticPage): RedirectResponse
    {
        $data = $request->validated();
        $data['alias'] = Str::slug($data['title'], '_');

        if($staticPage->update($data)){
            return redirect(route('admin.page.list'));
        }

        abort(500);
    }

    /**
     * Мягкое удаление
     */
    public function destroy(StaticPage $staticPage): RedirectResponse
    {
        if($staticPage->delete()){
            return redirect(route('admin.page.list'));
        }

        abort(500);
    }

    /**
     * Полное удаление
     */
    public function delete(string $id): RedirectResponse
    {
        $page = StaticPage::withTrashed()->find($id);
        if ($page) {
            $page->forceDelete();
        }

        return redirect(route('admin.page.list'));
    }

    /**
     * Восстановить запись
     */
    public function restoreModel(int $id): RedirectResponse
    {
        $page = StaticPage::withTrashed()->where('id', $id)->first();
        if ($page) {
            $page->restore();
        }

        return redirect()->back();
    }
}
