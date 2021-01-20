<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use Illuminate\Http\Request;

class OrganisationController extends Controller
{
    public function index()
    {
        $header = 'Организации';
        return view('organisations.admin.index',compact('header'));
    }


    public function form(Organisation  $organisation)
    {
        $header = '<a href="' . route('organisations.index') .'"> Организации </a> / Форма организации';
        $id = $organisation->id ?? 0;
        return view('organisations.admin.form',compact('organisation','header','id'));
    }


}
