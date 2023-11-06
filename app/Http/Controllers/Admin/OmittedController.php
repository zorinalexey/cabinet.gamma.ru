<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\OmittedService;
use App\Models\Fund;
use App\Models\Omitted;
use App\Models\Voting;
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
     * @return View|Application|Factory|App
     */
    public function index(): View|Application|Factory|App
    {
        return view('admin.omitted.list', [
            'active_omitteds' => Omitted::paginate(25),
            'delete_omitteds' => Omitted::onlyTrashed()->paginate(25)
        ]);
    }

    /**
     * Сохранить
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
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
     * @return View|Application|Factory|App
     */
    public function create(): View|Application|Factory|App
    {
        $funds = Fund::all();
        return view('admin.omitted.create', compact('funds'));
    }

    /**
     * @param mixed $values
     * @param array $data
     * @param $omitted
     * @return array
     */
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
     * @param string $id
     * @return View|Application|Factory|App
     */
    public function show(string $id): View|Application|Factory|App
    {
        return view('admin.omitted.show', ['omitted' => Omitted::find($id)]);
    }

    /**
     * Редактировать
     * @param string $id
     * @return View|Application|Factory|App
     */
    public function edit(string $id): View|Application|Factory|App
    {
        $omitted = Omitted::find($id);
        $funds = Fund::all();
        $count = count($omitted->votings);
        return view('admin.omitted.edit', compact('omitted', 'funds', 'count'));
    }

    /**
     * Сохранить изменения
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $omitted = Omitted::find($id);
        $data = OmittedService::omitted($request, $omitted);
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
    }

    /**
     * Мягкое удаление
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $omitted = Omitted::find($id);
        $omitted->delete();
        return redirect(route('admin_index', ['omitted']));
    }

    /**
     * Полное удаление
     * @param string $id
     * @return RedirectResponse
     */
    public function delete(string $id): RedirectResponse
    {
        $omitted = Omitted::withTrashed()->find($id);
        $omitted->forceDelete();
        return redirect(route('admin_index', ['omitted']));
    }

    /**
     * Восстановить запись
     * @param int $id
     * @return RedirectResponse
     */
    public function restoreModel(int $id): RedirectResponse
    {
        Omitted::withTrashed()->where('id', $id)->restore();
        return redirect()->back();
    }
}
