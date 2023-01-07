<?php

namespace Tests\Feature\Controllers;

use App\Enums\VerificationTypeEnum;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ServicesControllerTest extends TestCase
{
    use RefreshDatabase;

    use WithFaker;

    protected User $user;

    protected Service $service;

    protected string $privateKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->privateKey = Str::uuid();

        $this->service = Service::factory()
            ->for($this->user, 'user')
            ->create(
                [
                    'private_key' => Crypt::encrypt($this->privateKey)
                ]
            );

        $this->actingAs(
            $this->user
        );
    }

    public function testIndexServices(): void
    {
        $this->get(
            route('dashboard.services.index')
        )
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'status',
                'data' => [
                    'data' => [
                        [
                            'id',
                            'user_id',
                            'private_key',
                            'name',
                            'status',
                            'valid_until',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ]
            ]);
    }

    public function testShowServices(): void
    {
        $this->get(
            route('dashboard.services.show',['service' => $this->service->id])
        )
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'status',
                'data' => [

                    'id',
                    'user_id',
                    'private_key',
                    'name',
                    'status',
                    'valid_until',
                    'created_at',
                    'updated_at'

                ]
            ]);
    }

    public function testStoreService(): void
    {
        $data = [
            'name' => $this->faker()->name
        ];

        $this->post(
            route('dashboard.services.store'),
            $data
        )
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'name',
                    'status',
                    'valid_until',
                    'private_key',
                    'updated_at',
                    'created_at'
                ],
                'status',
                'code'
            ]);

        $this->assertDatabaseHas(
            Service::class,
            $data
        );

        $this->assertDatabaseCount(
            Service::class,
            2
        );
    }

    public function testUpdateService(): void
    {
        $data = [
            'name' => $this->faker()->name,
        ];

        $this->put(
            route('dashboard.services.update', ['service' => $this->service->id]),
            $data
        )
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'name',
                    'status',
                    'valid_until',
                    'private_key',
                    'updated_at',
                    'created_at'
                ],
                'status',
                'code'
            ]);

        $this->assertDatabaseCount(Service::class, 1);
        $this->assertDatabaseHas(Service::class, $data);
        $this->assertDatabaseMissing(Service::class, $this->service->toArray());
    }

    public function testDeleteService(): void
    {
        $this->delete(
            route('dashboard.services.destroy', ['service' => $this->service->id])
        )->assertOk()->assertJsonStructure(
            [
                'message',
                'status',
                'data',
                'code'
            ]
        );

        $this->assertDatabaseCount(Service::class, 0);
        $this->assertDatabaseMissing(Service::class, $this->service->toArray());
    }
}
