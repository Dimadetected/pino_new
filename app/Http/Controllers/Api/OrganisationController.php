<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OrganisationResource;
use App\Http\Services\OrganisationService;
use App\Models\Organisation;
use Illuminate\Http\Request;

class OrganisationController
{
    private $service;

    public function __construct()
    {
        $this->service = new OrganisationService();
    }

    public function index()
    {
        return OrganisationResource::collection($this->service->get());
    }

    public function store(Request $request)
    {
        return OrganisationResource::make($this->service->store($request->all()));
    }

    /**
     * Display the specified resource.
     * @param int $id
     */
    public function show(Organisation $organisation)
    {
        return OrganisationResource::make($organisation);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function update(Request $request, Organisation $organisation)
    {

        return OrganisationResource::make($this->service->update($organisation->id, $request->all()));
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
