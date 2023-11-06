<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Infinitum;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class InfinitumController extends Controller
{
    /**
     * Просмотр списка
     */
    public function index(): View|Application|Factory|App
    {
        return view('admin.infinitum.list', [
            'download_files' => Infinitum::where('download', '>', 0)->paginate(25),
            'new_files' => Infinitum::where('download', false)->paginate(25),
            'delete_files' => Infinitum::onlyTrashed()->paginate(25),
        ]);
    }

    /**
     * Мягкое удаление
     */
    public function destroy(string $id): RedirectResponse
    {
        Infinitum::find($id)->delete();

        return redirect(route('admin_index', ['infinitum']));
    }

    /**
     * Полное удаление
     */
    public function delete(string $id): RedirectResponse
    {
        $file = Infinitum::withTrashed()->find($id);
        if ($file) {
            $file->forceDelete();
        }

        return redirect(route('admin_index', ['infinitum']));
    }

    /**
     * Восстановить запись
     */
    public function restoreModel(int $id): RedirectResponse
    {
        Infinitum::withTrashed()->where('id', $id)->restore();

        return redirect()->back();
    }

    /**
     * @return void|null
     */
    public function upload(int $id)
    {
        $file = Infinitum::find($id);
        if ($file && Storage::drive('local')->exists($file->path)) {
            $xml = Storage::drive('local')->path($file->path);
            if ($this->downloadFile($xml)) {
                $file->download++;
                $file->save();

                return redirect()->route('admin_index', ['infinitum']);
            }
        }
        abort(404);
    }
}
