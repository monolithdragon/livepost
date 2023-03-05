<?php

namespace Tests\Feature\Api\V1\Comment;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        $user = User::factory()->make();
        $this->actingAs($user);
    }

    public function test_index()
    {
        $comments = Comment::factory(10)->create();
        $commentIds = $comments->map(fn ($comment) => $comment->id)->toArray();

        $response = $this->getJson(route('comments.index'))
            ->assertOk()
            ->json('data');

        collect($response)->each(fn ($comment) => $this->assertTrue(in_array($comment['id'], $commentIds)));
    }

    public function test_show()
    {
        $comment = Comment::factory()->create();

        $response = $this->getJson(route('comments.show', $comment))
            ->assertOk()
            ->json('data');

        $this->assertEquals(data_get($response, 'id'), $comment->id, 'Response ID not the same as model ID');
    }

    public function test_store()
    {
        $comment = Comment::factory()->make();

        $response = $this->postJson(route('comments.store'), $comment->toArray())
            ->assertCreated()
            ->json('data');

        $result = collect($response)->only(array_keys($comment->getAttributes()));

        $result->each(function ($value, $field) use ($comment) {
            $this->assertSame(data_get($comment, $field), $value, 'Fillable is not the same');
        });
    }

    public function test_update()
    {
        $comment = Comment::factory()->create();
        $updateComment = Comment::factory()->make();

        $fillables = collect((new Comment())->getFillable());

        $fillables->each(function ($updated) use ($comment, $updateComment) {
            $this->patchJson(route('comments.update', $comment), [
                $updated => data_get($updateComment, $updated)
            ])
                ->assertOk()
                ->json('data');

            $this->assertEquals(data_get($updateComment, $updated), data_get($comment->refresh(), $updated), 'Failed to update model.');
        });
    }

    public function test_destroy()
    {
        $comment = Comment::factory()->create();
        $this->deleteJson(route('comments.destroy', $comment))
            ->assertOk();

        $this->assertDatabaseMissing('comments', $comment->toArray());
    }
}