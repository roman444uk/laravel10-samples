<?php

namespace App\Http\Controllers\Orders;

use App\Data\CheckData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\CheckRejectRequest;
use App\Http\Requests\Orders\CheckStoreRequest;
use App\Http\Requests\Orders\CheckUpdateRequest;
use App\Models\Check;
use App\Models\Stage;
use App\Services\Db\Orders\CheckLifeCycleService;
use App\Services\Db\Orders\CheckService;

class CheckController extends Controller
{
    public function __construct(
        protected CheckService          $checkService,
        protected CheckLifeCycleService $checkLifeCycleService,
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
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CheckStoreRequest $request, Stage $stage)
    {
        $operationResponse = $this->checkService->store(CheckData::fromRequest($request), $stage);

        $check = $operationResponse->get('check');

        $checkData = $this->checkService->applySetupsFilesNames(CheckData::fromModel($check), $check, $stage);

        return successJsonResponse(null, ['check' => $checkData]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Check $check)
    {
        $check->load(['fileSetupTop', 'fileSetupBottom']);

        return successJsonResponse(null, [
            'check' => CheckData::fromModel($check),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Check $check)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CheckUpdateRequest $request, Check $check)
    {
        $operationResponse = $this->checkService->update($check, CheckData::fromRequest($request));

        $check = $operationResponse->get('check');

        $checkData = $this->checkService->applySetupsFilesNames(CheckData::fromModel($check), $check);

        return successJsonResponse(null, ['check' => $checkData]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Check $check)
    {
        return operationJsonResponse($this->checkService->destroy($check));
    }

    /**
     * Send check to verification.
     */
    public function sendToVerification(Check $check)
    {
        return operationJsonResponse($this->checkLifeCycleService->sendToVerification($check), ['check']);
    }

    /**
     * Accept check.
     */
    public function accept(Check $check)
    {
        return operationJsonResponse($this->checkLifeCycleService->accept($check), ['check']);
    }

    /**
     * Reject check.
     */
    public function reject(CheckRejectRequest $request, Check $check)
    {
        return operationJsonResponse(
            $this->checkLifeCycleService->reject($check, $request->validated('reject_reason')),
            ['check']
        );
    }

    /**
     * Recall from doctor's verification.
     */
    public function recallFromDoctor(Check $check)
    {
        return operationJsonResponse(
            $this->checkLifeCycleService->recallFromDoctorVerification($check),
            ['check']
        );
    }
}
