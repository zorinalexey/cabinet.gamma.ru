<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\UserFundService;
use App\Http\Services\UserService;
use App\Models\Fund;
use App\Models\User;
use App\Models\UserFund;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UsersController extends Controller
{
    public static function addFund(Request $request): RedirectResponse
    {
        $data = UserFundService::fundInfo($request);
        UserFund::updateOrCreate(
            $data,
            ['user_id' => $data['user_id'], 'fund_id' => $data['fund_id']]
        );

        return redirect()->back();
    }

    public static function dropFund(int $fund_id): RedirectResponse
    {
        $fund = UserFund::find($fund_id);
        $fund->forceDelete();

        return redirect()->back();
    }

    public static function editCountPif(Request $request): RedirectResponse
    {
        $data = UserFundService::fundInfo($request);
        $fund = UserFund::where('user_id', $data['user_id'])
            ->where('fund_id', $data['fund_id'])
            ->first();
        $fund->count_pif = $data['count_pif'];
        $fund->save();

        return redirect()->back();
    }

    public static function check(int $id): RedirectResponse
    {
        $user = User::find($id);
        $user->check();

        return redirect()->back();
    }

    /**
     * Просмотр списка
     */
    public function index(): View|Application|Factory|App
    {
        $active_users = [];
        $delete_users = [];
        $activeCollection = User::paginate(25);
        foreach ($activeCollection as $user) {
            $active_users[] = $user;
        }
        $delCollection = User::onlyTrashed()->paginate(25);
        foreach ($delCollection as $user) {
            $delete_users[] = $user;
        }

        return view('admin.users.list', compact('active_users', 'delete_users', 'delCollection', 'activeCollection'));
    }

    /**
     * Сохранить
     */
    public function store(Request $request): RedirectResponse
    {
        $user = User::create(UserService::user($request));
        UserService::saveUser($request, $user);

        return redirect(route('admin_index', ['users']));
    }

    /**
     * Создать
     */
    public function create(): View|Application|Factory|App
    {
        $dadata = config('dadata');

        return view('admin.users.create', compact('dadata'));
    }

    /**
     * Посмотреть
     */
    public function show(string $id): View|Application|Factory|App
    {
        return view('admin.users.show', ['user' => User::find($id), 'funds' => Fund::all()]);
    }

    /**
     * Редактировать
     */
    public function edit(string $id): View|Application|Factory|App
    {
        $dadata = config('dadata');

        return view('admin.users.edit', ['user' => User::find($id), 'dadata' => $dadata]);
    }

    /**
     * Сохранить изменения
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $user = User::find($id);
        $user->update(
            UserService::user($request)
        );
        UserService::saveUser($request, $user);

        return redirect(route('admin_index', ['users']));
    }

    /**
     * Мягкое удаление
     */
    public function destroy(string $id): RedirectResponse
    {
        $user = User::find($id);
        $user->delete();

        return redirect(route('admin_index', ['users']));
    }

    /**
     * Полное удаление
     */
    public function delete(string $id): RedirectResponse
    {
        $user = User::withTrashed()->find($id);
        $user->forceDelete();

        return redirect(route('admin_index', ['users']));
    }

    /**
     * Восстановить запись
     */
    public function restoreModel(int $id): RedirectResponse
    {
        User::withTrashed()->where('id', $id)->restore();

        return redirect()->back();
    }
}
