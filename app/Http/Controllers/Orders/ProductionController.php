<?php

namespace App\Http\Controllers\Orders;

use App\Data\ProductionData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\ProductionRequest;
use App\Models\Production;
use App\Models\Stage;
use App\Services\Db\Orders\ProductionService;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function __construct(
        protected ProductionService $productionService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductionRequest $request)
    {
        return operationJsonResponse(
            $this->productionService->store(ProductionData::fromRequest($request), $request->check),
            ['production']
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Production $production)
    {
        return successJsonResponse(null, [
            'production' => ProductionData::fromModel($production),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Production $production)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductionRequest $request, Production $production)
    {
        return operationJsonResponse(
            $this->productionService->update($production, ProductionData::fromRequest($request)),
            ['production']
        );
    }

    /**
     * Send production to work.
     */
    public function sendToWork(Production $production)
    {
        return operationJsonResponse($this->productionService->sendToWork($production));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Production $production)
    {
        return operationJsonResponse($this->productionService->destroy($production));
    }
}
