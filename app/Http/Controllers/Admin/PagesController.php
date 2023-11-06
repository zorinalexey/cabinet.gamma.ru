<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            '_token' => ['required'],
            'title' => ['required'],
            'content' => ['required'],
        ]);
        unset($data['_token']);
        $data['alias'] = Str::slug($data['title'], '_');
        $page = new StaticPage();
        foreach ($data as $key => $value) {
            $page->$key = $value;
        }
        $page->save();

        return redirect(route('admin_index', ['pages']));
    }

    /**
     * Редактировать
     */
    public function edit(string $id): View|Application|Factory|App
    {
        $page = StaticPage::find($id);

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Сохранить изменения
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $page = StaticPage::find($id);
        $data = $request->validate([
            '_token' => ['required'],
            'title' => ['required'],
            'content' => ['required'],
        ]);
        unset($data['_token']);
        $data['alias'] = Str::slug($data['title'], '_');
        foreach ($data as $key => $value) {
            $page->$key = $value;
        }
        $page->save();

        return redirect(route('admin_index', ['pages']));
    }

    /**
     * Мягкое удаление
     */
    public function destroy(string $id): RedirectResponse
    {
        $page = StaticPage::find($id);
        if ($page) {
            $page->delete();
        }

        return redirect(route('admin_index', ['pages']));
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

        return redirect(route('admin_index', ['pages']));
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
