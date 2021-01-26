<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController
{
    private $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function index()
    {
        return UserResource::collection($this->service->get());
    }

    public function store(Request $request)
    {
        return UserResource::make($this->service->store($request->all()));
    }

    /**
     * Display the specified resource.
     * @param int $id
     */
    public function show(User $user)
    {
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function update(Request $request, User $user)
    {

        return UserResource::make($this->service->update($user->id, $request->all()));
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
