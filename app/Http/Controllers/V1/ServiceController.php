<?php

namespace App\Http\Controllers\V1;

use App\Enums\ServiceStatusEnum;
use App\Enums\ServiceTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteServiceRequest;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceCollection;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\Services\ServicesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ServiceController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Service::class, 'service');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->successResponse(
            new ServiceCollection(Auth::user()->services()->paginate(Arr::get($request->all(), 'per_page')))
        );
    }

    /**
     * @param \App\Http\Requests\StoreServiceRequest $request
     * @param \App\Services\Services\ServicesService $servicesService
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreServiceRequest $request, ServicesService $servicesService): JsonResponse
    {
        $data = $request->validated();
        try {
            $service = $servicesService->assignData(
                name: Arr::get($data, 'name'),
                type: ServiceTypeEnum::from(Arr::get($data, 'type')),
                status: ServiceStatusEnum::ACTIVE,
                valid_until: Carbon::now()->addMonth(),
                user: Auth::user(),
                private_key: Str::uuid()
            );
            return $this->successResponse(
                new ServiceResource($service),
                __('Service was successfully stored.'),
                1001
            );
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(__('Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param \App\Models\Service $service
     * @param \App\Http\Requests\UpdateServiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateServiceRequest $request, Service $service): JsonResponse
    {
        $data = $request->validated();
        try {

            $service = (new ServicesService($service))->assignData(
                name: Arr::get($data, 'name'),
                type: ServiceTypeEnum::from(Arr::get($data, 'type')),
                status: ServiceStatusEnum::ACTIVE,
                valid_until: Carbon::now()->addMonth(),
                user: Auth::user()
            );

            return $this->successResponse(
                new ServiceResource($service),
                __('Successfully updated the service.'),
                1002
            );

        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(__('Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param \App\Http\Requests\DeleteServiceRequest $request
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteServiceRequest $request, Service $service): JsonResponse
    {

        try {
            $service->delete();

            return $this->successResponse(
                new ServiceCollection(Auth::user()->services()->paginate(Arr::get($request->all(), 'per_page'))),
                __('successfully deleted the service.'),
                1003
            );
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(__('Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
