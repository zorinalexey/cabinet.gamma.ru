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

/**
 *
 */
final class UsersController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public static function addFund(Request $request): RedirectResponse
    {
        $data = UserFundService::fundInfo($request);
        UserFund::updateOrCreate(
            $data,
            ['user_id' => $data['user_id'], 'fund_id' => $data['fund_id']]
        );
        return redirect()->back();
    }

    /**
     * @param int $fund_id
     * @return RedirectResponse
     */
    public static function dropFund(int $fund_id): RedirectResponse
    {
        $fund = UserFund::find($fund_id);
        $fund->forceDelete();
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
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

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public static function check(int $id): RedirectResponse
    {
        $user = User::find($id);
        $user->check();
        return redirect()->back();
    }

    /**
     * Просмотр списка
     * @return View|Application|Factory|App
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
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $user = User::create(UserService::user($request));
        UserService::saveUser($request, $user);
        return redirect(route('admin_index', ['users']));
    }

    /**
     * Создать
     * @return View|Application|Factory|App
     */
    public function create(): View|Application|Factory|App
    {
        $dadata = config('dadata');
        return view('admin.users.create', compact('dadata'));
    }

    /**
     * Посмотреть
     * @param string $id
     * @return View|Application|Factory|App
     */
    public function show(string $id): View|Application|Factory|App
    {
        return view('admin.users.show', ['user' => User::find($id), 'funds' => Fund::all()]);
    }

    /**
     * Редактировать
     * @param string $id
     * @return View|Application|Factory|App
     */
    public function edit(string $id): View|Application|Factory|App
    {
        $dadata = config('dadata');
        return view('admin.users.edit', ['user' => User::find($id), 'dadata' => $dadata]);
    }

    /**
     * Сохранить изменения
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
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
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $user = User::find($id);
        $user->delete();
        return redirect(route('admin_index', ['users']));
    }

    /**
     * Полное удаление
     * @param string $id
     * @return RedirectResponse
     */
    public function delete(string $id): RedirectResponse
    {
        $user = User::withTrashed()->find($id);
        $user->forceDelete();
        return redirect(route('admin_index', ['users']));
    }

    /**
     * Восстановить запись
     * @param int $id
     * @return RedirectResponse
     */
    public function restoreModel(int $id): RedirectResponse
    {
        User::withTrashed()->where('id', $id)->restore();
        return redirect()->back();
    }
}
