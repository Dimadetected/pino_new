<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ClientResource;
use App\Http\Services\ClientService;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController
{
    private $service;

    public function __construct()
    {
        $this->service = new ClientService();
    }

    public function index()
    {
        $inn = \request("inn");
        return ClientResource::collection($this->service->get($inn));
    }

    public function store(Request $request)
    {
        return ClientResource::make($this->service->store($request->all()));
    }

    /**
     * Display the specified resource.
     * @param int $id
     */
    public function show(Client $client)
    {
        return ClientResource::make($client);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function update(Request $request, Client $client)
    {

        return ClientResource::make($this->service->update($client->id, $request->all()));
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
