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

/**
 *
 */
final class PagesController extends Controller
{
    /**
     * Просмотр списка
     * @return View|Application|Factory|App
     */
    public function index(): View|Application|Factory|App
    {
        return view('admin.pages.list', [
            'active_pages' => StaticPage::paginate(25),
            'delete_pages' => StaticPage::onlyTrashed()->paginate(25)
        ]);
    }

    /**
     * Создать
     * @return View|Application|Factory|App
     */
    public function create(): View|Application|Factory|App
    {
        return view('admin.pages.create');
    }

    /**
     * Сохранить
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            '_token' => ['required'],
            'title' => ['required'],
            'content' => ['required']
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
     * @param string $id
     * @return View|Application|Factory|App
     */
    public function edit(string $id): View|Application|Factory|App
    {
        $page = StaticPage::find($id);
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Сохранить изменения
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $page = StaticPage::find($id);
        $data = $request->validate([
            '_token' => ['required'],
            'title' => ['required'],
            'content' => ['required']
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
     * @param string $id
     * @return RedirectResponse
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
     * @param string $id
     * @return RedirectResponse
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
     * @param int $id
     * @return RedirectResponse
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
