<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillResource;
use App\Models\Bill;
use App\Models\BillAction;
use App\Models\BillStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class BillController extends Controller
{
    
    public function index()
    {
        $user = User::query()->find(\request('user_id'));
        if ($user->user_role_id == 4)
            $user->user_role_id = 5;
        
        $bills = BillResource::collection(
            Bill::query()
                ->where('bill_type_id', $user->user_role_id)
                ->orWhere('user_id',$user->id)
                ->whereNotIn('status', [2])
                ->with(['user', 'bill_type', 'bill_status'])
                ->get());
        return $bills;
    }
    
    
    
    
    public function store(Request $request)
    {
        //п
    }
    
    /**
     * Display the specified resource.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    
    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
