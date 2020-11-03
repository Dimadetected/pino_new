<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillFormRequest;
use App\Models\Bill;
use App\Models\BillAction;
use App\Models\BillStatus;
use App\Models\User;
use App\Models\UserRole;
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
        $user = auth()->user();
        
        
        $bills =   Bill::query()
            ->where('bill_type_id', $user->user_role_id)
            ->orWhere('user_id',$user->id)
            ->with(['user', 'bill_type', 'bill_status'])
            ->get();
        
        $header = 'Счета';
        $action = '<a class="btn btn-success" href=' . route($this->routes['form']) .' style="float: right">Создать</a>';
        return view($this->views['index'], compact('bills','user','header','action'))->with('routes',$this->routes);
    }
    
    public function view(Bill $bill)
    {
        $user = auth()->user();
        $header = 'Инфомарция о счете';
        return view($this->views['view'], compact('bill', 'user','header'));
    }
    
    
    public function form(Bill $bill)
    {
//        $user = auth()->user();
        $header = 'Форма счета';
        return view($this->views['form'], compact('bill','header'))->with('routes', $this->routes);
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
        $bill->text = $request->text;
        $bill->file_id = $file->id;
        if (is_null($bill->bill_type_id))
            $bill->bill_type_id = 1;
        
        if (is_null($bill->bill_status_id))
            $bill->bill_status_id = 1;
        
        if (is_null($bill->user_id))
            $bill->user_id = $user->id;
        
        $bill->save();
        
        return redirect()->route($this->routes['view'], $bill);
    }
    
    public function consult(Bill $bill)
    {
        $type = \request('type');
        if ($type == 'accept') {
            $status = 1;
            $bill_status_id = $bill->goodNextStatus();
            $text = BillStatus::query()->find($bill->goodNextStatus())->name;
            $return = 'good';
        } else {
            $status = 2;
            $bill_status_id = $bill->badNextStatus();
            $text = BillStatus::query()->find($bill->badNextStatus())->name;
            $return = 'bad';
        }
        
        $billArr = [
            'status' => $status,
            'bill_status_id' => $bill_status_id,
        ];
        
        if ($return == 'good')
            $billArr['bill_type_id'] = $bill->bill_type_id + 1;
        
        $bill->update($billArr);
        BillAction::query()->create([
            'bill_id' => $bill->id,
            'user_id' => auth()->user()->id,
            'text' => $text,
        ]);
        
        return redirect()->back();
    }
}
