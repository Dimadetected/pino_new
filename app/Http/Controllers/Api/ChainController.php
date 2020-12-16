<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillResource;
use App\Http\Resources\ChainResource;
use App\Http\Resources\UserRoleResource;
use App\Http\Service\ChainService;
use App\Http\Service\UserRoleService;
use App\Models\Bill;
use App\Models\BillAction;
use App\Models\BillStatus;
use App\Models\Chain;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ChainController extends Controller
{
    private $service;
    
    public function __construct()
    {
        $this->service = new ChainService();
    }
    
    public function index()
    {
        return ChainResource::collection($this->service->get());
    }
    
    public function store(Request $request)
    {
        return ChainResource::make($this->service->store($request->all()));
    }
    
    /**
     * Display the specified resource.
     * @param int $id
     */
    public function show(Chain $chain)
    {
        return ChainResource::make($chain);
    }
    
    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function update(Request $request, Chain $chain)
    {
        return ChainResource::make($this->service->update($chain->id, $request->all()));
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
