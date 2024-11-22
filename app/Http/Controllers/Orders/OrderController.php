<?php

namespace App\Http\Controllers\Orders;

use App\Classes\Helpers\Db\AddressHelper;
use App\Classes\Helpers\Db\ClinicHelper;
use App\Classes\Helpers\Db\PatientHelper;
use App\Classes\Helpers\Db\StageHelper;
use App\Data\Filters\OrderDataFilter;
use App\Data\OrderData;
use App\Enums\Orders\StageTabEnum;
use App\Enums\StageSubTabEnum;
use App\Enums\System\SessionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderRequest;
use App\Models\Doctor;
use App\Models\Order;
use App\Models\Repositories\OrderRepository;
use App\Models\Stage;
use App\Models\User;
use App\Services\Db\Chat\ChatService;
use App\Services\Db\Orders\OrderService;
use App\Services\Db\Orders\PatientService;
use App\Services\Db\Users\DoctorClinicService;
use App\Traits\Controllers\Paginatable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use Paginatable;

    public function __construct(
        protected ChatService           $chatService,
        protected DoctorClinicService   $doctorClinicService,
        protected OrderService          $orderService,
        protected PatientService        $patientService,
    )
    {
        $this->authorizeResource(Order::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FormRequest $request, OrderRepository $orderRepository)
    {
        $orderFilter = OrderDataFilter::fromRequest($request, [
            'requester_role' => getUserRole(),
            'doctor_id' => getDoctorId(),
            'client_manager_id' => getClientManagerId(),
            'clinical_specialist_id' => getClinicalSpecialistId(),
            'modeler_3d_id' => getModeler3dId(),
            'technician_digitizer_id' => getTechnicianDigitizerId(),
            'technician_production_id' => getTechnicianProductionId(),
            'logistic_manager_id' => getLogisticManagerId(),
        ])
            ->pageSize($this->getPageSize(null, $request));

        return view('pages.order.index', [
            'clientManagersCollection' => User::isClientManager()->get(),
            'clinicalSpecialistsCollection' => User::isClinicalSpecialist()->get(),
            'doctorsCollection' => !isUserDoctor() ? Doctor::get() : null,
            'ordersCollection' => $orderRepository->paginate($orderFilter, [
                'doctor.user', 'doctor.clientManager'
            ]),
            'orderFilter' => $orderFilter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.order.create', [
            'patientsCollection' => PatientHelper::getSelectOptions(getDoctor()->patients, ''),
            'clinicsCollection' => ClinicHelper::getSelectOptions(getDoctor()->clinics, ''),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        return operationJsonResponse(
            $this->orderService->store(OrderData::fromRequest($request), getDoctor()), ['order']
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('pages.order.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Order $order, Stage $stage = null, string $tab = null, string $subTab = null)
    {
        $doctor = getDoctor() ?? $order->doctor;

        $order->loadStages();

        $stage = $stage ?? $order->stages[0];
        $tab = $tab ?? StageTabEnum::TREATMENT_PLAN->value;
        $subTab = $subTab ?? StageSubTabEnum::UPLOAD_SCAN->value;

        $this->chatService->getOrderChatInternal($order);

        $stage->loadAllPhotos()->loadCheckAccepted();

        return view('pages.order.edit', [
            'order' => $order,
            'stage' => $stage,
            'isReadyForWork' => StageHelper::isReadyForWork($stage),
            'tab' => $tab,
            'subTab' => $subTab,
            'chatWithLaboratory' => $this->chatService->getOrderChatWithDoctor($order),
            'patientsCollection' => PatientHelper::getSelectOptions($doctor->patients),
            'clinicsCollection' => ClinicHelper::getSelectOptions($doctor->clinics),
            'clientManagersCollection' => User::isClientManager()->get(),
            'clinicalSpecialistsCollection' => User::isClinicalSpecialist()->get(),
            'logisticManagersCollection' => User::isLogisticManager()->get(),
            'technicianDigitizersCollection' => User::isTechnicianDigitizer()->get(),
            'technicianProductionsCollection' => User::isTechnicianProduction()->get(),
            'modelers3dCollection' => User::isModeler3d()->get(),
            'addressesCollection' => AddressHelper::getSelectOptions($doctor->addresses, ''),
            'chatOffCanvasWidth' => session()->get(SessionEnum::CHAT_OFF_CANVAS_WIDTH->value),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order)
    {
        return operationJsonResponse(
            $this->orderService->update($order, OrderData::fromRequest($request)), ['order']
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        return operationJsonResponse(
            $this->orderService->destroy($order), ['order']
        );
    }
}
