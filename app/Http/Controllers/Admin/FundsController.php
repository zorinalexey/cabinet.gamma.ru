<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\FundService;
use App\Models\Fund;
use App\Models\User;
use App\Models\UserFund;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     *
     * @throws JsonException
     */
    public function store(Request $request): RedirectResponse
    {
        $data = FundService::getCreateFund($request);
        Fund::create($data);

        return redirect(route('admin_index', ['funds']));
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
    public function show(string $id): View|Application|Factory|App
    {
        return view('admin.funds.show', ['fund' => Fund::find($id)]);
    }

    /**
     * Редактировать
     */
    public function edit(string $id): View|Application|Factory|App
    {
        $fund = Fund::find($id);
        $fund->access_users = json_decode($fund->access_users) ?: [];
        $users = User::all();

        return view('admin.funds.edit', compact('fund', 'users'));
    }

    /**
     * Сохранить изменения
     *
     * @throws JsonException
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $fund = Fund::find($id);
        $data = FundService::getEditFund($request);
        $fund->update($data);

        return redirect(route('admin_index', ['funds']));
    }

    /**
     * Мягкое удаление
     */
    public function destroy(string $id): RedirectResponse
    {
        $fund = Fund::find($id);
        if ($fund) {
            $funds = $fund->users;
            foreach ($funds as $user_fund) {
                $user_fund->delete();
            }
            $fund->delete();
        }

        return redirect(route('admin_index', ['funds']));
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

        return redirect(route('admin_index', ['funds']));
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
