<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->successResponse(
            new ServiceResource(Auth::user())
        );
    }

    /**
     * @param \App\Http\Requests\UpdateProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $data = $request->validated();
        try {
            (new UserService(Auth::user()))->assignData([
                    'full_name' => Arr::get($data, 'full_name'),
                    'password' => Arr::get($data, 'new_password')
                ]
            );
            return $this->successResponse(
                new UserResource(Auth::user()),
                __('Successfully updated profile.'),
                1001
            );
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(__('Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
