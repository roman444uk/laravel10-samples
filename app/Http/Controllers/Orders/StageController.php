<?php

namespace App\Http\Controllers\Orders;

use App\Classes\Helpers\Db\StageHelper;
use App\Data\StageData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stage\StageCreateRequest;
use App\Http\Requests\Stage\StageTakeCastsRequest;
use App\Http\Requests\Stage\StageUpdateRequest;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Stage;
use App\Services\Db\Orders\PatientService;
use App\Services\Db\Orders\StageLifeCycleService;
use App\Services\Db\Orders\StageService;
use App\Traits\Controllers\JsonRespondent;
use Illuminate\Http\Request;

class StageController extends Controller
{
    use JsonRespondent;

    public function __construct(
        protected PatientService        $patientService,
        protected StageLifeCycleService $stageLifeCycleService,
        protected StageService          $stageService,
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
    public function create(Request $request, Order $order)
    {
        return operationJsonResponse(
            $this->stageService->store($order, $request->get('type'))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StageCreateRequest $request, Order $order)
    {
        return operationJsonResponse(
            $this->stageService->store($order, $request->get('type')), ['stage']
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Stage $stage)
    {
        return successJsonResponse(null, [
            'patient' => StageData::fromModel($stage),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StageUpdateRequest $request, Stage $stage)
    {
        $operationResponse = $this->stageService->update($stage, StageData::fromRequest($request));

        return operationJsonResponse($operationResponse, null, [
            'isReadyForWork' => StageHelper::isReadyForWork($operationResponse->get('stage')),
            'stage' => $operationResponse->get('stage'),
        ]);
    }

    /**
     * Send stage to work.
     */
    public function sendToWork(Stage $stage)
    {
        $checkResponse = $this->stageService->isReadyForWork($stage);

        if (!$checkResponse->isSuccess()) {
            return errorJsonResponse($checkResponse->getMessage(), $checkResponse->getErrors());
        }

        return operationJsonResponse($this->stageService->sendToWork($stage));
    }

    /**
     * Save info about casts taking.
     */
    public function takeCasts(StageTakeCastsRequest $request, Stage $stage)
    {
        return operationJsonResponse($this->stageService->takeCasts($stage, StageData::fromRequest($request)));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
