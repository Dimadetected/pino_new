<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillFormRequest;
use App\Models\Bill;
use App\Models\BillAction;
use App\Models\BillStatus;
use App\Models\BillType;
use App\Models\Chain;
use App\Models\Message;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;

class BillController extends Controller
{
    private $views = [
        'index' => 'bills.admin.index',
        'view' => 'bills.admin.view',
        'form' => 'bills.admin.form',
    ];
    private $routes = [
        'index' => 'bill.index',
        'view' => 'bill.view',
        'form' => 'bill.form',
        'store' => 'bill.store',
    ];
    
    public function check()
    {
        return 1;
    }
    
    public function index()
    {
        $date_start = Carbon::parse(\request('date_start', now()->startOfYear()))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', now()->endOfYear()))->endOfDay();
        $user = auth()->user();
        
        $bills = Bill::query()
            ->where('user_role_id', $user->user_role_id)
            ->orWhere('user_id', $user->id)
            ->with(['user', 'bill_type', 'bill_status','chain'])
            ->orderBy('created_at', 'desc');
        
        $bills = $bills->whereBetween('created_at', [$date_start, $date_end])->get();
        $header = 'Счета';
        $action = '<a class="btn btn-success" href=' . route($this->routes['form']) . ' style="float: right">Создать</a>';
        return view($this->views['index'], compact('date_start', 'date_end', 'bills', 'user', 'header', 'action'))->with('routes', $this->routes);
    }
    
    public function view(Bill $bill)
    {
        $user = auth()->user();
        $header = 'Инфомарция о счете';
        
        return view($this->views['view'], compact('bill', 'user', 'header'));
    }
    
    public function form(Bill $bill)
    {
//        $user = auth()->user();
        $chains = Chain::query()->get();
        $header = 'Форма счета';
        return view($this->views['form'], compact('bill', 'header','chains'))->with('routes', $this->routes);
    }
    
    public function consult()
    {
        $bill = Bill::query()->find(\request('bill'));
        $billArr = [];
        $type = \request('type');
        $bill_status = $bill->bill_statuses();
        if ($type == 'accept') {
            $status = 1;
            $bill_status = $bill_status->where('status', 'good')->first();
            $bill_status_id = $bill_status->id;
            $text = $bill_status->name;
            $billArr['steps'] = $bill->steps + 1;
            if (isset($bill->chain->value[$billArr['steps']]))
                $billArr['user_role_id'] = $bill->chain->value[$billArr['steps']];
            else
                $billArr['user_role_id'] = NULL;
            $return = 'good';
        } else {
            $bill_status = $bill_status->where('status', 'bad')->first();
            $status = 2;
            $bill_status_id = $bill_status->id;
            $text = $bill_status->name;
            $return = 'bad';
        }
        $billArr['status'] = $status;
        $billArr['bill_status_id'] = $bill_status_id;
        
        if ($return == 'good' and isset($billArr['bill_type_id']))
            $billArr['bill_type_id'] = BillType::query()->where('user_role_id', $billArr['user_role_id'])->first()->id;
        $bill->bill_log()->create([
            'info' => [
                'status' => $bill->status,
                'bill_type_id' => $bill->bill_type_id,
                'user_role_id' => $bill->user_role_id,
                'bill_status_id' => $bill->bill_status_id,
                'steps' => $bill->steps,
            ],
        ]);
        $bill->update($billArr);
        
        $action = BillAction::query()->create([
            'bill_id' => $bill->id,
            'user_id' => auth()->user()->id,
            'status' => $status,
            'text' => $text,
        ]);
        
        if (\request('text'))
            Message::query()->create([
                'type' => 'bill_action',
                'external_id' => $action->id,
                'user_id' => auth()->user()->id,
                'text' => \request('text'),
            ]);
        
        return response()->json($bill);
    }
    
    public function back(Bill $bill)
    {
        $log = $bill->bill_log;
        $bill->update($log->info);
        $bill->bill_action->create([
            'bill_id' => $bill->id,
            'user_id' => 31,
            'status' => $log->info['status'],
            'text' => 'Изменения были отменены',
        ]);
        return redirect()->back();
    }
    
    public function store(BillFormRequest $request, Bill $bill)
    {
        $user = auth()->user();
        $files = [];
        foreach ($request->file()['files'] as $file) {
            if (!is_dir(public_path('files')))
                mkdir(public_path('files'), 0777, TRUE);
            
            File::put(public_path('files/' . $file->getClientOriginalName()), file_get_contents($file));
            $files[] = 'files/' . $file->getClientOriginalName();
        }
        $file = \App\Models\File::query()->create([
            'src' => $files,
        ]);
//        $bill->steps = 1;
//        dd($user);
//        if (in_array($user->user_role_id, [2, 7])) {
//            $bill->steps = 2;
//        }
        
        $chain = Chain::query()->find($request->chain_id);
        $bill->steps = 0;
        $bill->chain_id = $chain->id;
        $bill->user_role_id = $chain->value[$bill->steps];
        
        $bill->text = $request->text;
        $bill->file_id = $file->id;
        $bill->bill_type_id = BillType::query()->where('user_role_id', $chain->value[$bill->steps])->first()->id;
        
        if (is_null($bill->user_id))
            $bill->user_id = $user->id;
        
        $bill->save();
        
        return redirect()->route($this->routes['view'], $bill);
    }
    
    public function accept()
    {
        $user = auth()->user();
        $date_start = Carbon::parse(\request('date_start', now()->startOfYear()))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', now()->endOfYear()))->endOfDay();
        $bills = Bill::query()
            ->where('user_role_id', $user->user_role_id)
            ->whereBetween('created_at', [$date_start, $date_end])
            ->where('status', 1)
            ->with(['user', 'bill_type', 'bill_status','chain'])
            ->get();
        
        $header = 'Счета для подтверждения';
        $action = '<a class="btn btn-success" href=' . route($this->routes['form']) . ' style="float: right">Создать</a>';
        return view($this->views['index'], compact('date_end', 'date_start', 'bills', 'user', 'header', 'action'))->with('routes', $this->routes);
    }
    
    public function accepted()
    {
        $user = auth()->user();
        $actions = BillAction::query()->where('user_id', $user->id)->pluck('bill_id')->toArray();
        $date_start = Carbon::parse(\request('date_start', now()->startOfYear()))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', now()->endOfYear()))->endOfDay();
        $bills = Bill::query()
            ->whereIn('id', $actions)
            ->whereBetween('created_at', [$date_start, $date_end])
            ->with(['user', 'bill_type', 'bill_status','chain'])
            ->get();
        
        $header = 'Подтвержденные счета';
        $action = '<a class="btn btn-success" href=' . route($this->routes['form']) . ' style="float: right">Создать</a>';
        return view($this->views['index'], compact('date_start', 'date_end', 'bills', 'user', 'header', 'action'))->with('routes', $this->routes);
    }
    
    public function my()
    {
        $user = auth()->user();
        $date_start = Carbon::parse(\request('date_start', now()->startOfYear()))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', now()->endOfYear()))->endOfDay();
        $bills = Bill::query()
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$date_start, $date_end])
            ->with(['user', 'bill_type', 'bill_status','chain'])
            ->get();
        
        $header = 'Мои счета';
        $action = '<a class="btn btn-success" href=' . route($this->routes['form']) . ' style="float: right">Создать</a>';
        return view($this->views['index'], compact('date_start', 'date_end', 'bills', 'user', 'header', 'action'))->with('routes', $this->routes);
    }
    
    public function delete(Bill $bill)
    {
        $bill->delete();
        return redirect()->route('bill.index');
    }
}
