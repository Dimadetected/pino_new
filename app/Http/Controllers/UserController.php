<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        if (auth()->user()->user_role_id != 1){
            abort(401);
        }

        $header = 'Пользователи';
        $action = '<a class="btn btn-success" href=' . route("users.create") . ' style="float: right">Создать</a>';

        return view('users.admin.index',compact('header','action'));
    }


    public function form(User  $user)
    {
        if (auth()->user()->user_role_id != 1){
            abort(401);
        }

        $header = '<a href="' . route('users.index') .'"> Пользователи </a> / Форма пользователя';
        $id = $user->id ?? 0;
        return view('users.admin.form',compact('user','header','id'));
    }

    public function create()
    {
        if (auth()->user()->user_role_id != 1){
            abort(401);
        }

        $header = '<a href="' . route('users.index') .'"> Пользователи </a> / Создания пользователя';
        return view('users.admin.create',compact('header'));
    }


}
