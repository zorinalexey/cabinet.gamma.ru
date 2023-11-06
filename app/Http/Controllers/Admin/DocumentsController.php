<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserDocument;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

final class DocumentsController extends Controller
{
    /**
     * Просмотр списка
     */
    public function index(): View|Application|Factory|App
    {
        $active_docs = UserDocument::orderBy('id', 'DESC')->orderBy('user_id', 'ASC')->paginate(25);
        $delete_docs = UserDocument::onlyTrashed()->orderBy('id', 'DESC')->orderBy('user_id', 'ASC')->paginate(25);

        $activeUserDocs = [];
        $i = 0;
        foreach ($active_docs as $doc) {
            $activeUserDocs[$doc->user_id][$i] = $doc->getAddModels();
            $activeUserDocs[$doc->user_id][$i]['doc'] = $doc;
            $activeUserDocs[$doc->user_id][$i]['doc']->status = $doc->getStatus();
            $i++;
        }

        $deleteUserDocs = [];
        foreach ($delete_docs as $doc) {
            $deleteUserDocs[$doc->user_id][$i] = $doc->getAddModels();
            $deleteUserDocs[$doc->user_id][$i]['doc'] = $doc;
            $deleteUserDocs[$doc->user_id][$i]['doc']->status = $doc->getStatus();
            $i++;
        }

        return view('admin.documents.list', compact('active_docs', 'delete_docs', 'activeUserDocs', 'deleteUserDocs'));
    }

    /**
     * @return void|null
     */
    public function upload(int $id)
    {
        $file = UserDocument::find($id);
        $path = str_replace('storage/', '', $file->path);
        if ($file && Storage::drive('local')->exists($path)) {
            $xml = Storage::drive('local')->path($path);
            if ($this->downloadFile($xml)) {
                return redirect()->route('admin_index', ['documents']);
            }
        }
        abort(404);
    }

    /**
     * Восстановить запись
     */
    public function restoreModel(int $id): RedirectResponse
    {
        UserDocument::withTrashed()->where('id', $id)->restore();

        return redirect()->back();
    }

    /**
     * Мягкое удаление
     */
    public function destroy(string $id): RedirectResponse
    {
        UserDocument::find($id)->delete();

        return redirect(route('admin_index', ['documents']));
    }
}
