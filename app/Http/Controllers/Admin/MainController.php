<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class MainController extends Controller
{
    public function index()
    {
        $users = [];
        $collection = User::paginate(25);
        foreach ($collection as $user) {
            $users[] = $user;
        }

        return view('admin.main', compact('users', 'collection'));
    }
}
