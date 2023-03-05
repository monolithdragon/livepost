<?php

namespace Tests\Feature\Api\V1\User;

use App\Events\Models\User\UserCreated;
use App\Events\Models\User\UserDeleted;
use App\Events\Models\User\UserUpdated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp() : void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function test_index()
    {
        $users = User::factory(10)->create();
        $userIds = $users->map(fn ($user) => $user->id)->toArray();

        $response = $this->getJson(route('users.index'))
            ->assertOk()
            ->json('data');

        collect($response)->each(fn ($user) => $this->assertTrue(in_array($user['id'], $userIds)));
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $response = $this->getJson(route('users.show', $user))
            ->assertOk()
            ->json('data');

        $this->assertEquals(data_get($response, 'id'), $user->id, 'Response ID not the same as model ID');
    }

    public function test_store()
    {
        Event::fake();
        $user = User::factory()->make();

        $response = $this->postJson(route('users.store'), $user->toArray())
            ->assertStatus(201)
            ->json('data');

        Event::assertDispatched(UserCreated::class);

        $result = collect($response)->only(array_keys($user->getAttributes()));

        $result->each(function ($value, $field) use ($user) {
            $this->assertSame(data_get($user, $field), $value, 'Fillable is not the same');
        });
    }

    public function test_update()
    {
        Event::fake();
        $user = User::factory()->create();
        $updateUser = User::factory()->make();

        $fillables = collect((new User())->getFillable());

        $fillables->each(function ($updated) use ($user, $updateUser) {
            $this->patchJson(route('users.update', $user), [
                $updated => data_get($updateUser, $updated)
            ])
                ->assertOk()
                ->json('data');

            Event::assertDispatched(UserUpdated::class);

            $this->assertEquals(data_get($updateUser, $updated), data_get($user->refresh(), $updated), 'Failed to update model.');
        });
    }

    public function test_destroy()
    {
        Event::fake();
        $user = User::factory()->create();
        $this->deleteJson(route('users.destroy', $user))
            ->assertOk();

        Event::assertDispatched(UserDeleted::class);

        $this->assertDatabaseMissing('users', $user->toArray());
    }
}