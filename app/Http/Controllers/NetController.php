<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillFormRequest;
use App\Http\Services\SmsService;
use App\Http\Services\TgService;
use App\Mail\OrderShipped;
use App\Models\Bill;
use App\Models\BillAction;
use App\Models\BillStatus;
use App\Models\BillType;
use App\Models\Chain;
use App\Models\Client;
use App\Models\Message;
use App\Models\Net;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Exception;
use Gufy\PdfToHtml\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use setasign\Fpdi\Fpdi;
use ZendPdf\Color\Html;
use ZendPdf\Font;
use ZendPdf\Image;
use ZendPdf\PdfDocument;

class NetController extends Controller
{
    private $views = [
        'index' => 'net.index',
        'view' => 'net.view',
        'form' => 'net.form',
    ];
    private $routes = [
        'index' => 'net.index',
        'delete' => 'net.delete',
        'form' => 'net.form',
        'store' => 'net.store',
    ];


    public function index()
    {
//        if (auth()->user()->user_role_id != 1){
//            abort(401);
//        }

        $nets = Net::all();
        $header = 'Сети';
        $action = '<a class="btn btn-success" href=' . route($this->routes['form']) . ' style="float: right">Создать</a>';
        return view($this->views['index'],
            compact('header', 'action','nets'))->with('routes', $this->routes);
    }


    public function form(Net $net)
    {
//        if (auth()->user()->user_role_id != 1){
//            abort(401);
//        }

        $header = '<a href="' . route($this->routes['index']) . '">Сети</a> / Редактирование';
        return view($this->views['form'],
            compact('header', 'net'))->with('routes', $this->routes);
    }

    public function delete(Net $net)
    {
        if (auth()->user()->user_role_id != 1){
            abort(401);
        }

        Net::query()->find($net->id)->delete();
        return redirect()->route($this->routes['index']);
    }

    public function store(Request $request)
    {
//        if (auth()->user()->user_role_id != 1){
//            abort(401);
//        }

        $request->validate([
            "name" => "required"
        ]);

        Net::query()->updateOrCreate([
            "id" => $request->id
        ], [
            "name" => $request->name,
        ]);

        return redirect()->route($this->routes['index']);
    }
}
