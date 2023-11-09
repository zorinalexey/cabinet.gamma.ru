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
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class OmittedController extends Controller
{
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
        $data = OmittedService::omitted($request);
        $omitted = Omitted::create($data);
        OmittedService::omitted($request, $omitted);
        foreach (OmittedService::votings($request) as $voting) {
            $data = self::getVotingData($voting, $data, $omitted);
            Voting::create($data);
        }

        return redirect(route('admin_index', ['omitted']));
    }

    /**
     * Создать
     */
    public function create(): View|Application|Factory|App
    {
        $funds = Fund::all();

        return view('admin.omitted.create', compact('funds'));
    }

    private static function getVotingData(mixed $values, array $data, $omitted): array
    {
        $data['type_transaction'] = $values['type_transaction'];
        $data['parties_transaction'] = $values['parties_transaction'];
        $data['subject_transaction'] = $values['subject_transaction'];
        $data['cost_transaction'] = $values['cost_transaction'];
        $data['other_conditions'] = $values['other_conditions'];
        $data['omitted_id'] = $omitted->id;
        $data['fund_id'] = $omitted['fund_id'];

        return $data;
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
        dump($request->validated());
        /**
        $omitted->update($data);
        $votings = OmittedService::votings($request);
        foreach ($votings as $voting_id => $values) {
            $data = self::getVotingData($values, $data, $omitted);
            $votingModel = Voting::where('omitted_id', $omitted->id)
                ->where('id', $voting_id)->first();
            if ($votingModel) {
                $votingModel->update($data);
            } else {
                Voting::create($data);
            }
        }

        return redirect(route('admin_index', ['omitted']));
        */
    }

    /**
     * Мягкое удаление
     */
    public function destroy(Omitted $omitted): RedirectResponse
    {
        $omitted->delete();

        return redirect(route('admin_index', ['omitted']));
    }

    /**
     * Полное удаление
     */
    public function delete(string $id): RedirectResponse
    {
        $omitted = Omitted::withTrashed()->find($id);
        $omitted->forceDelete();

        return redirect(route('admin_index', ['omitted']));
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
