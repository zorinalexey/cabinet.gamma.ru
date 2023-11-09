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
    public function destroy(Infinitum $infinitum): RedirectResponse
    {
        $infinitum->delete();

        return redirect(route('admin.infinitum.list'));
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

        return redirect(route('admin.infinitum.list'));
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
    public function upload(Infinitum $infinitum)
    {
        if ($infinitum && Storage::drive('local')->exists($infinitum->path)) {
            $xml = Storage::drive('local')->path($infinitum->path);
            if ($this->downloadFile($xml)) {
                $infinitum->download++;
                $infinitum->save();

                return redirect()->route(url()->previous());
            }
        }
        abort(404);
    }
}
