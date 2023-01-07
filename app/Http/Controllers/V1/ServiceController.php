<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceCollection;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Repositories\ServiceRepositoryInterface;
use App\Services\Services\ServicesService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ServiceController extends Controller
{
    public function __construct(
        private readonly ServicesService $servicesService,
        private readonly ServiceRepositoryInterface $serviceRepository
    ) {
        $this->authorizeResource(Service::class, 'service');
    }

    public function index(Request $request): JsonResponse
    {
        $services = $this->serviceRepository
            ->getServicesByUserAndPaginate(
                Auth::user(),
                $request->get('per_page', 10)
            );

        return $this->successResponse(
            new ServiceCollection(
                $services
            )
        );
    }

    public function show(Service $service): JsonResponse
    {
        return $this->successResponse(
            new ServiceResource(
                $service
            )
        );
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        try {
            $service = $this->servicesService->assignData($request->toDto());

            return $this->successResponse(
                new ServiceResource($service),
                __('messages.Service was successfully stored.'),
                1001
            );
        } catch (Exception $e) {
            $this->reportError($e);

            return $this->errorResponse(
                __('messages.Something went wrong.'),
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update(UpdateServiceRequest $request, Service $service): JsonResponse
    {
        try {
            $service = $this->servicesService
                ->setInstance($service)
                ->assignData(
                    $request->toDto()
                );

            return $this->successResponse(
                new ServiceResource($service),
                __('messages.Successfully updated the service.'),
                1002
            );
        } catch (Exception $e) {
            $this->reportError($e);

            return $this->errorResponse(
                __('messages.Something went wrong.'),
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function destroy(Service $service): JsonResponse
    {
        try {
            $service->delete();

            return $this->successResponse(
                message: __('messages.Successfully deleted the service.'),
                code: 1003
            );
        } catch (Exception $e) {
            $this->reportError($e);

            return $this->errorResponse(
                __('messages.Something went wrong.'),
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
