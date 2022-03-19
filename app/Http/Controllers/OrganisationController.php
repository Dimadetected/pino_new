<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use Illuminate\Http\Request;

class OrganisationController extends Controller
{
    public function index()
    {
        if (auth()->user()->user_role_id != 1){
            abort(401);
        }

        $header = 'Организации';
        return view('organisations.admin.index',compact('header'));
    }


    public function form(Organisation  $organisation)
    {
        if (auth()->user()->user_role_id != 1){
            abort(401);
        }

        $header = '<a href="' . route('organisations.index') .'"> Организации </a> / Форма организации';
        $id = $organisation->id ?? 0;
        return view('organisations.admin.form',compact('organisation','header','id'));
    }


}
