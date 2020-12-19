<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillFormRequest;
use App\Models\Bill;
use App\Models\BillAction;
use App\Models\BillStatus;
use App\Models\Message;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;

class ChainController extends Controller
{
    private $views = [
        'index' => 'chains.admin.index',
        'form' => 'chains.admin.form',
    ];
    
    public function index()
    {
        $header = 'Цепочки';
    
        return view($this->views['index'], compact('header'));
    }
    
    public function form($id = 0)
    {
        $header = 'Конструктор цепочек';
        
        return view($this->views['form'], compact('header', 'id'));
    }
    
}
