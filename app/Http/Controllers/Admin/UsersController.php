<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserFundRequest;
use App\Http\Requests\EditUserFundRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Services\UserFundService;
use App\Http\Services\UserService;
use App\Models\Fund;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserFund;
use App\Models\UserInn;
use App\Models\UserPassport;
use App\Models\UserSnils;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

final class UsersController extends Controller
{
    public static function addFund(User $user, AddUserFundRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $user->id;

        if($fund = $user->funds()->where('fund_id', $data['fund_id'])->first()){
            $fund->count_pif += $data['count_pif'];
            if($fund->save()){
                return redirect()->back();
            }
        }

        if(UserFund::query()->create($data)){
            return redirect()->back();
        }

        abort(500);
    }

    public static function dropFund(User $user, UserFund $userFund): RedirectResponse
    {
        if($userFund->forceDelete()){
            return redirect()->back();
        }

        abort(500);
    }

    public static function editCountPif(User $user, UserFund $userFund, EditUserFundRequest $request): RedirectResponse
    {
        if($userFund->update($request->validated())){
            return redirect()->back();
        }

        abort(500);
    }

    public static function check(User $user): RedirectResponse
    {
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
    public function store(UserCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if($user = User::query()->create($data)){
            $data['user_id'] = $user->id;
            if(
                UserPassport::query()->create($data) &&
                UserInn::query()->create(['user_id' => $user->id, 'number' => $data['inn']]) &&
                UserSnils::query()->create(['user_id' => $user->id, 'number' => $data['snils']]) &&
                UserAddress::query()->create(['user_id' => $user->id, 'address' => $data['reg_addr'], 'address_status' => 0]) &&
                UserAddress::query()->create(['user_id' => $user->id, 'address' => $data['fact_addr'], 'address_status' => 1])
            ){
                return redirect(route('admin.user.list'));
            }
        }

        abort(500);
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
    public function show(User $user): View|Application|Factory|App
    {
        return view('admin.users.show', ['user' => $user, 'funds' => Fund::all()]);
    }

    /**
     * Редактировать
     */
    public function edit(User $user): View|Application|Factory|App
    {
        $dadata = config('dadata');

        return view('admin.users.edit', compact('user', 'dadata'));
    }

    /**
     * Сохранить изменения
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        foreach ($data as $k => $v){
            if(!$v){
                Arr::pull($data, $k);
            }
        }

        $user->update($data);
        $user->passport?->update($data);

        if(isset($data['inn'])) {
            $user->inn?->update(['number' => $data['inn']]);
        }
        if(isset($data['snils'])) {
            $user->snils?->update(['number' => $data['snils']]);
        }
        if(isset($data['reg_addr'])) {
            $user->address_registration()?->update(['address' => $data['reg_addr'], 'address_status' => 0]);
        }
        if(isset($data['fact_addr'])) {
            $user->address_fact()?->update(['address' => $data['fact_addr'], 'address_status' => 1]);
        }

        return redirect(route('admin.user.list'));
    }

    /**
     * Мягкое удаление
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect(route('admin.user.list'));
    }

    /**
     * Полное удаление
     */
    public function delete(int $id): RedirectResponse
    {
        $user = User::withTrashed()->find($id);
        $user->forceDelete();

        return redirect(route('admin.user.list'));
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
