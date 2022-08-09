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

class ApplicationController extends Controller
{
    private $views = [
        'index' => 'applications.admin.index',
        'view' => 'applications.admin.view',
        'form' => 'applications.admin.form',
    ];
    private $routes = [
        'index' => 'application.index',
        'view' => 'bill.view',
        'form' => 'bill.form',
        'store' => 'application.store',
    ];

    public function check()
    {
        return 1;
    }

    public function __construct()
    {
        $this->telegram = new TgService(env("TGBOT"));
        $this->sms = new SmsService();
    }

    public function index()
    {
        $billsCreators = User::query()->orderBy("name", "asc")->get();
        $contragents = Client::query()->orderBy("name", "asc")->get();

        $billCreatorID = \request("bill_creator_id", 0);
        $contragentID = \request("contragent_id", 0);
        $billNumber = \request("bill_number", 0);
        $date_start = Carbon::parse(\request('date_start', ($_COOKIE['bill_date_start'] ?? now()->startOfYear())))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', ($_COOKIE['bill_end_start'] ?? now()->endOfYear())))->endOfDay();
        setcookie('bill_date_start', $date_start);
        setcookie('bill_end_start', $date_end);
        $user = auth()->user();
        if (is_null($user->remember_token))
            $user->update(['remember_token' => rand(111111111, 99999999999)]);
        $org_ids = $user->org_ids;
        $user_id = $user->id;
        $bills = Bill::query()
            ->orderByDesc('id')
            ->where('user_role_id', $user->user_role_id)
            ->orWhere('user_id', $user->id)
            ->with(['user', 'bill_type', 'bill_status', 'bill_actions'])
            ->with('bill_alerts', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->with('chain', function ($query) {
                $query->where('type', 2);
            })
            ->orderBy('created_at', 'desc');
        $bills = $bills->whereBetween('created_at', [$date_start, $date_end])->get();
        if ($billNumber != 0 and $billNumber != "") {
            $bills = $bills->where("id", "=", $billNumber);
        }
        if ($contragentID != 0) {
            $bills = $bills->where("client_id", "=", $contragentID);
        }
        if ($billCreatorID != 0) {
            $bills = $bills->where("user_id", "=", $billCreatorID);
        }

        $worked = \request("worked","");
        if ($worked != ""){
            $bills = $bills->where("worked",$worked);
        }
        $header = 'Заявки';
        $bill_type = 'заявки';
        $action =  $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
            '<a class="btn btn-success" href=' . route($this->routes['form'], ["type" => 1]) . ' style="float: right">Создать счет</a>' .
            '<a class="btn btn-primary ml-2" href=' . route($this->routes['form'], ["type" => 2]) . ' style="float: right">Создать заявку</a>' .
            '</div>';
        return view($this->views['index'],
            compact('bill_type','org_ids', 'date_start', 'date_end','worked', 'bills', 'user', 'header', 'action', 'billsCreators', 'contragents', 'billNumber', 'contragentID', 'billCreatorID'))->with('routes', $this->routes);
    }

    public function accept()
    {
        $billsCreators = User::query()->orderBy("name", "asc")->get();
        $contragents = Client::query()->orderBy("name", "asc")->get();

        $billCreatorID = \request("bill_creator_id", 0);
        $contragentID = \request("contragent_id", 0);
        $billNumber = \request("bill_number", 0);

        $user = auth()->user();
        $org_ids = $user->org_ids;
        $user_id = $user->id;
        $date_start = Carbon::parse(\request('date_start', ($_COOKIE['bill_date_start'] ?? now()->startOfYear())))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', ($_COOKIE['bill_end_start'] ?? now()->endOfYear())))->endOfDay();
        setcookie('bill_date_start', $date_start);
        setcookie('bill_end_start', $date_end);
        $bills = Bill::query()
            ->orderByDesc('id')
            ->where('user_role_id', $user->user_role_id)
            ->whereBetween('created_at', [$date_start, $date_end])
            ->where('status', 1)
            ->with(['user', 'bill_type', 'bill_status'])
            ->with('bill_alerts', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->with('chain', function ($query) {
                $query->where('type', 2);
            });
        if ($billNumber != 0 and $billNumber != "") {
            $bills = $bills->where("id", "=", $billNumber);
        }
        if ($contragentID != 0) {
            $bills = $bills->where("client_id", "=", $contragentID);
        }
        if ($billCreatorID != 0) {
            $bills = $bills->where("user_id", "=", $billCreatorID);
        }
        $worked = \request("worked","");
        if ($worked != ""){
            $bills = $bills->where("worked",$worked);
        }
        $bills = $bills->get();
        $bill_type = 'заявки';
        $header = 'Заявки для подтверждения';
        $action =  $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
            '<a class="btn btn-success" href=' . route($this->routes['form'], ["type" => 1]) . ' style="float: right">Создать счет</a>' .
            '<a class="btn btn-primary ml-2" href=' . route($this->routes['form'], ["type" => 2]) . ' style="float: right">Создать заявку</a>' .
            '</div>';
        return view($this->views['index'], compact('bill_type','date_end','worked', 'org_ids', 'date_start', 'bills', 'user', 'header', 'action', 'billsCreators', 'contragents', 'billNumber', 'contragentID', 'billCreatorID'))->with('routes', $this->routes);
    }

    public function accepted()
    {
        $billsCreators = User::query()->orderBy("name", "asc")->get();
        $contragents = Client::query()->orderBy("name", "asc")->get();

        $billCreatorID = \request("bill_creator_id", 0);
        $contragentID = \request("contragent_id", 0);
        $billNumber = \request("bill_number", 0);

        $user = auth()->user();
        $org_ids = $user->org_ids;
        $user_id = $user->id;
        $actions = BillAction::query()->where('user_id', $user->id)->pluck('bill_id')->toArray();
        $date_start = Carbon::parse(\request('date_start', ($_COOKIE['bill_date_start'] ?? now()->startOfYear())))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', ($_COOKIE['bill_end_start'] ?? now()->endOfYear())))->endOfDay();
        setcookie('bill_date_start', $date_start);
        setcookie('bill_end_start', $date_end);
        $bills = Bill::query()
            ->orderByDesc('id')
            ->whereIn('id', $actions)
            ->whereBetween('created_at', [$date_start, $date_end])
            ->with(['user', 'bill_type', 'bill_status'])
            ->with('bill_alerts', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->with('chain', function ($query) {
                $query->where('type', 2);
            })
            ->where('status', 1);
        if ($billNumber != 0 and $billNumber != "") {
            $bills = $bills->where("id", "=", $billNumber);
        }
        if ($contragentID != 0) {
            $bills = $bills->where("client_id", "=", $contragentID);
        }
        if ($billCreatorID != 0) {
            $bills = $bills->where("user_id", "=", $billCreatorID);
        }
        $worked = \request("worked","");
        if ($worked != ""){
            $bills = $bills->where("worked",$worked);
        }
        $bills = $bills->get();

        $header = 'Подтвержденные заявки';
        $bill_type = 'заявки';

        $action =  $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
            '<a class="btn btn-success" href=' . route($this->routes['form'], ["type" => 1]) . ' style="float: right">Создать счет</a>' .
            '<a class="btn btn-primary ml-2" href=' . route($this->routes['form'], ["type" => 2]) . ' style="float: right">Создать заявку</a>' .
            '</div>';
        return view($this->views['index'], compact('bill_type','org_ids','worked', 'date_start', 'date_end', 'bills', 'user', 'header', 'action', 'billsCreators', 'contragents', 'billNumber', 'contragentID', 'billCreatorID'))->with('routes', $this->routes);
    }

    public function my()
    {
        $billsCreators = User::query()->orderBy("name", "asc")->get();
        $contragents = Client::query()->orderBy("name", "asc")->get();

        $billCreatorID = \request("bill_creator_id", 0);
        $contragentID = \request("contragent_id", 0);
        $billNumber = \request("bill_number", 0);

        $user = auth()->user();
        $date_start = Carbon::parse(\request('date_start', ($_COOKIE['bill_date_start'] ?? now()->startOfYear())))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', ($_COOKIE['bill_end_start'] ?? now()->endOfYear())))->endOfDay();
        setcookie('bill_date_start', $date_start);
        setcookie('bill_end_start', $date_end);
        $user_id = $user->id;
        $bills = Bill::query()
            ->orderByDesc('id')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$date_start, $date_end])
            ->with(['user', 'bill_type', 'bill_status', 'bill_actions'])
            ->with('bill_alerts', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->with('chain', function ($query) {
                $query->where('type', 2);
            });
        if ($billNumber != 0 and $billNumber != "") {
            $bills = $bills->where("id", "=", $billNumber);
        }
        if ($contragentID != 0) {
            $bills = $bills->where("client_id", "=", $contragentID);
        }
        if ($billCreatorID != 0) {
            $bills = $bills->where("user_id", "=", $billCreatorID);
        }
        $worked = \request("worked","");
        if ($worked != ""){
            $bills = $bills->where("worked",$worked);
        }
        $bills = $bills->get();
        $org_ids = $user->org_ids;

        $header = 'Мои заявки';
        $bill_type = 'заявки';
        $action =  $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
            '<a class="btn btn-success" href=' . route($this->routes['form'], ["type" => 1]) . ' style="float: right">Создать счет</a>' .
            '<a class="btn btn-primary ml-2" href=' . route($this->routes['form'], ["type" => 2]) . ' style="float: right">Создать заявку</a>' .
            '</div>';
        return view($this->views['index'], compact('bill_type','date_start', 'worked','org_ids', 'date_end', 'bills', 'user', 'header', 'action', 'billsCreators', 'contragents', 'billNumber', 'contragentID', 'billCreatorID'))->with('routes', $this->routes);
    }

    public function worked(Request $request, Bill $bill){
        $bill->update([
            "worked" => $request->worked
        ]);
        return redirect()->back();
    }
}
