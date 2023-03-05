<?php

namespace Tests\Unit;

use App\Exceptions\GeneralJsonException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;
    private $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->repository = $this->app->make(UserRepository::class);
        $this->user = User::factory(1)->create()->first();
    }

    public function test_create()
    {
        $payload = [
            'name' => 'heyaa',
            'email' => 'abc@example.com',
            'password' => 'secret',
        ];

        $result = $this->repository->create($payload);

        $this->assertSame($payload['name'], $result->name, 'User created does not have the same title.');
    }

    public function test_update()
    {
        $payload = [
            'name' => 'abc123',
        ];

        $updated = $this->repository->update($this->user, $payload);
        $this->assertSame($payload['name'], $updated->name, 'User updated does not have the same title.');
    }

    public function test_delete_will_throw_exception_when_delete_post_that_does_not_exists()
    {
        $user = User::factory(1)->make()->first();

        $this->expectException(GeneralJsonException::class);
        $this->repository->forceDelete($user);
    }

    public function test_delete()
    {
        $this->repository->forceDelete($this->user);
        $found = User::query()->find($this->user->id);

        $this->assertSame(null, $found, 'User is not deleted.');
    }
}