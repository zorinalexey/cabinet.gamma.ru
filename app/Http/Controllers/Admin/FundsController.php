<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FundCreateRequest;
use App\Http\Requests\FundUpdateRequest;
use App\Models\Fund;
use App\Models\User;
use App\Models\UserFund;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use JsonException;

final class FundsController extends Controller
{
    /**
     * Просмотр списка
     */
    public function index(): View|Application|Factory|App
    {
        return view('admin.funds.list', [
            'active_funds' => Fund::paginate(25),
            'delete_funds' => Fund::onlyTrashed()->paginate(25),
        ]);
    }

    /**
     * Сохранить
     */
    public function store(FundCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (!isset($data['current_cost_one_pif'])) {
            $data['current_cost_one_pif'] = 0;
        }

        if (Fund::query()->create($data)) {
            return redirect(route('admin.fund.list'));
        }

        abort(500);
    }

    /**
     * Создать
     */
    public function create(): View|Application|Factory|App
    {
        $users = User::all();

        return view('admin.funds.create', compact('users'));
    }

    /**
     * Посмотреть
     */
    public function show(Fund $fund): View|Application|Factory|App
    {
        return view('admin.funds.show', compact('fund'));
    }

    /**
     * Редактировать
     */
    public function edit(Fund $fund): View|Application|Factory|App
    {
        $fund->access_users = json_decode($fund->access_users) ?: [];
        $users = User::all();

        return view('admin.funds.edit', compact('fund', 'users'));
    }

    /**
     * Сохранить изменения
     */
    public function update(FundUpdateRequest $request, Fund $fund): RedirectResponse
    {

        $data = $request->validated();

        foreach ($data as $k => $v) {
            if (empty($v)) {
                Arr::pull($data, $k);
            }
        }

        if ($fund->update($data)) {
            return redirect(route('admin.fund.list'));
        }

        abort(500);
    }

    /**
     * Мягкое удаление
     */
    public function destroy(Fund $fund): RedirectResponse
    {
        foreach ($fund->users as $user_fund) {
            $user_fund->delete();
        }

        $fund->delete();

        return redirect(route('admin.fund.list'));
    }

    /**
     * Полное удаление
     */
    public function delete(string $id): RedirectResponse
    {
        $fund = Fund::withTrashed()->find($id);
        if ($fund) {
            $funds = $fund->users;
            foreach ($funds as $user_fund) {
                $user_fund->forceDelete();
            }
            $fund->forceDelete();
        }

        return redirect(route('admin.fund.list'));
    }

    /**
     * Восстановить запись
     */
    public function restoreModel(int $id): RedirectResponse
    {
        Fund::withTrashed()->where('id', $id)->restore();
        $fund = Fund::find($id);
        if ($fund) {
            UserFund::withTrashed()->where('fund_id', $id)->restore();
        }

        return redirect()->back();
    }
}
