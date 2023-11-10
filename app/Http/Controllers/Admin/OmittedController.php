<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OmittedCreateRequest;
use App\Http\Requests\OmittedUpdateRequest;
use App\Http\Services\OmittedService;
use App\Models\Fund;
use App\Models\Omitted;
use App\Models\Voting;
use App\Services\Omitted\OmittedDocumentsService;
use Exception;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class OmittedController extends Controller
{
    private static function sync(Omitted $omitted, array $data):bool
    {

        foreach ($data['votings'] as $key => $value){
            $data['votings'][$key]['omitted_id'] = $omitted->id;
            $data['votings'][$key]['fund_id'] = $omitted->fund->id;
        }

        $voitingSync = $omitted->votings()->sync($data['votings']);

        if(isset($data['file'])){
            $file = $data['file'];

            $name = "Решение о проведении голосования №{$omitted->id}";
            $filePath = "/storage/omitteds/{$omitted->id}/".Str::slug($name, '_').'.'.preg_replace('~^(.+)\.(\w{2,5})$~ui', '$2', $_FILES['file']['name']);
            $dir = public_path().dirname($filePath);

            if(!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }

            if(file_put_contents(public_path().$filePath, $file->getContent())){
                $docSync = $omitted->documents()->sync([['link' => $filePath, 'name' => $name]]);

                if($docSync && $voitingSync){
                    return true;
                }

                return false;
            }
        }

        if($voitingSync){
            return true;
        }

        return  false;
    }

    /**
     * Просмотр списка
     */
    public function index(): View|Application|Factory|App
    {
        return view('admin.omitted.list', [
            'active_omitteds' => Omitted::paginate(25),
            'delete_omitteds' => Omitted::onlyTrashed()->paginate(25),
        ]);
    }

    /**
     * Сохранить
     */
    public function store(OmittedCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            return DB::transaction(static function () use ($data){
                if(($omitted = Omitted::query()->create($data)) && self::sync($omitted, $data)){
                    return redirect(route('admin.omitted.list'));
                }

                return redirect()->back();
            });
        }catch (Exception $e){

        }

        abort(500);
    }

    /**
     * Создать
     */
    public function create(): View|Application|Factory|App
    {
        $funds = Fund::all();

        return view('admin.omitted.create', compact('funds'));
    }

    /**
     * Посмотреть
     */
    public function show(string $id): View|Application|Factory|App
    {
        return view('admin.omitted.show', ['omitted' => Omitted::find($id)]);
    }

    /**
     * Редактировать
     */
    public function edit(Omitted $omitted): View|Application|Factory|App
    {
        $funds = Fund::all();
        $count = count($omitted->votings);

        return view('admin.omitted.edit', compact('omitted', 'funds', 'count'));
    }

    /**
     * Сохранить изменения
     */
    public function update(OmittedUpdateRequest $request, Omitted $omitted)#: RedirectResponse
    {

        $data = $request->validated();

        try {
            return DB::transaction(static function () use ($omitted, $data) {
                if(($omitted->update($data)) && self::sync($omitted, $data)){
                    return redirect(route('admin.omitted.list'));
                }

                return redirect()->back();
            });
        }catch (Exception $e){

        }

        abort(500);
    }

    /**
     * Мягкое удаление
     */
    public function destroy(Omitted $omitted): RedirectResponse
    {
        $omitted->delete();

        return redirect(route('admin.omitted.list'));
    }

    /**
     * Полное удаление
     */
    public function delete(string $id): RedirectResponse
    {
        $omitted = Omitted::withTrashed()->find($id);
        $omitted->documents()->forceDelete();
        $omitted->votings()->forceDelete();
        $omitted->forceDelete();

        return redirect(route('admin.omitted.list'));
    }

    /**
     * Восстановить запись
     */
    public function restoreModel(int $id): RedirectResponse
    {
        Omitted::withTrashed()->where('id', $id)->restore();

        return redirect()->back();
    }

    public function generateProtocol(Omitted $omitted): RedirectResponse|null
    {
        $service = new OmittedDocumentsService();
        if($service->generateProtocol($omitted)){
            return back();
        }

        abort(404);
    }
}
