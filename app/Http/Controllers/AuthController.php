<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    private $views = [
        'login' => 'auth.login',
        'register' => 'auth.register',
    ];
    
    private $routes = [
        'login' => 'login',
        'register' => 'register',
        'store' => 'store',
    
    ];
    
    public function register()
    {
        return view($this->views['register'])->with('routes', $this->routes);
    }
    
    public function store(RegisterRequest $request)
    {
        $user = User::query()->where('email', $request->get('email'))->first();
        $vallArr = [];
        if (isset($user))
            $vallArr['email_isset'] = 'required';
        if ($request->get('password') != $request->get('password_repeat'))
            $vallArr['double_password'] = 'required';
        $request->validate($vallArr);
        
        $user = User::query()->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'user_role_id' => 1,
        ]);
        
        Cookie::queue(Cookie::make('billu', $user->id, 3600 * 24));
        return redirect()->route('bill.index');
    }
    
    public function login()
    {
    
    }
}
