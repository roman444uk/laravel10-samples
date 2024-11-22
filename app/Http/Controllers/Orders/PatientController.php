<?php

namespace App\Http\Controllers\Orders;

use App\Classes\Helpers\Db\ClinicHelper;
use App\Classes\Helpers\Db\PatientHelper;
use App\Classes\ServicesResponses\OperationResponse;
use App\Data\PatientData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\PatientRequest;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Stage;
use App\Services\Db\Orders\PatientService;
use App\Traits\Controllers\JsonRespondent;

class PatientController extends Controller
{
    use JsonRespondent;

    public function __construct(
        protected PatientService $patientService
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientRequest $request)
    {
        return $this->operationResponse(
            $request,
            $this->patientService->store(PatientData::fromRequest($request), getDoctor())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return successJsonResponse(null, [
            'patient' => PatientData::fromModel($patient),
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
    public function update(PatientRequest $request, Patient $patient)
    {
        return $this->operationResponse(
            $request,
            $this->patientService->update($patient, PatientData::fromRequest($request))
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }

    /**
     * Returns form data after saving record
     */
    protected function operationResponse(PatientRequest $request, OperationResponse $operationResponse)
    {
        $order = $request->get('order') ? Order::findOrFail($request->get('order')) : null;
        $stage = $request->get('stage') ? Stage::findOrFail($request->get('stage')) : null;
        $patient = $operationResponse->get('patient');

        return operationJsonResponse($operationResponse, null, [
            'patient' => $patient->toArray(),
            'form' => (string)view('pages.order.form.index', [
                'order' => $order,
                'stage' => $stage,
                'orderEditionDisabled' => false,
                'stageEditionDisabled' => false,
                'tab' => $request->get('tab'),
                'subTab' => null,
                'chatWithLaboratory' => null,
                'patient' => $patient,
                'clinicsCollection' => ClinicHelper::getSelectOptions(getDoctor()->clinics, ''),
                'patientsCollection' => PatientHelper::getSelectOptions(getDoctor()->patients, '')
            ]),
        ]);
    }
}
