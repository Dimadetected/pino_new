<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillResource;
use App\Http\Resources\UserRoleResource;
use App\Http\Services\UserRoleService;
use App\Models\Bill;
use App\Models\BillAction;
use App\Models\BillStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UserRoleController extends Controller
{
    private  $service;
    public function __construct() {
        $this->service = new UserRoleService();
    }

    public function index()
    {
        return UserRoleResource::collection($this->service->get());
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
