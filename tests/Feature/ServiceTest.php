<?php

namespace Tests\Feature;

use App\Enums\ServiceStatusEnum;
use App\Enums\ServiceTypeEnum;
use App\Models\Company;
use App\Models\Service;
use App\Models\User;
use App\Services\Services\ServicesService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ServiceTest extends TestCase
{

    use DatabaseTransactions;
    use WithFaker;

    /**
     * @var Collection|Model|User
     */

    protected User|Collection|Model $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_get_services_list()
    {
        $user = User::factory()
            ->has(Service::factory()->count(1))
            ->createOne();

        Sanctum::actingAs(
            $user
        );

        $response = $this->get(
            route("dashboard.services.index")
        );
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'status',
            'data' => [
                'data' => [
                    [
                        "id",
                        "uuid",
                        "user_id",
                        "private_key",
                        "name",
                        "type",
                        "status",
                        "valid_until",
                        "created_at",
                        "updated_at"
                    ]
                ],
                "pagination" => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages'
                ]
            ]
        ]);

    }

    public function test_add_service()
    {
        Sanctum::actingAs(
            $this->user
        );


        $response = $this->post(
            route("dashboard.services.store"),
            [
                'name' => $this->faker()->name,
                'type' => ServiceTypeEnum::TEXT->value
            ]
        );
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'user_id',
                'name',
                'type',
                'status',
                'valid_until',
                'private_key',
                'updated_at',
                'created_at'
            ],
            'status',
            'code'
        ]);

    }

    public function test_update_service()
    {
        $user = User::factory()
            ->has(Service::factory()->count(1))
            ->createOne();

        Sanctum::actingAs(
            $user
        );


        $response = $this->put(
            route("dashboard.services.update", ['service' => $user->services()->first()->id]),
            [
                'name' => $this->faker()->name,
                'type' => ServiceTypeEnum::TEXT->value
            ]
        );
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'user_id',
                'name',
                'type',
                'status',
                'valid_until',
                'private_key',
                'updated_at',
                'created_at'
            ],
            'status',
            'code'
        ]);


    }

    public function test_remove_service()
    {
        $user = User::factory()
            ->has(Service::factory()->count(1))
            ->createOne();

        Sanctum::actingAs(
            $user
        );

        $response = $this->delete(
            route("dashboard.services.destroy", ['service' => $user->services()->first()->id])
        );


        // dd($user->services->first()->id);
        $response->assertStatus(200);
    }
}
