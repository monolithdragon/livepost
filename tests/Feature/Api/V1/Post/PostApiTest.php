<?php

namespace Tests\Feature\Api\V1\Post;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function test_index()
    {
        $posts = Post::factory(10)->create();
        $postIds = $posts->map(fn ($post) => $post->id)->toArray();

        $response = $this->getJson(route('posts.index'))
            ->assertOk()
            ->json('data');

        collect($response)->each(fn ($post) => $this->assertTrue(in_array($post['id'], $postIds)));
    }

    public function test_show()
    {
        $post = Post::factory()->create();
        $response = $this->getJson(route('posts.show', $post))
            ->assertOk()
            ->json('data');

        $this->assertEquals(data_get($response, 'id'), $post->id, 'Response ID not the same as model ID');
    }

    public function test_store()
    {
        $post = Post::factory()->make();

        $response = $this->postJson(route('posts.store'), $post->toArray())
            ->assertStatus(201)
            ->json('data');

        $result = collect($response)->only(array_keys($post->getAttributes()));

        $result->each(function ($value, $field) use ($post) {
            $this->assertSame(data_get($post, $field), $value, 'Fillable is not the same');
        });
    }

    public function test_update()
    {
        $post = Post::factory()->create();
        $updatePost = Post::factory()->make();

        $fillables = collect((new Post())->getFillable());

        $fillables->each(function ($updated) use ($post, $updatePost) {
            $this->patchJson(route('posts.update', $post), [
                $updated => data_get($updatePost, $updated)
            ])
                ->assertOk()
                ->json('data');

            $this->assertEquals(data_get($updatePost, $updated), data_get($post->refresh(), $updated), 'Failed to update model.');
        });
    }

    public function test_destroy()
    {
        $post = Post::factory()->create();
        $this->deleteJson(route('posts.destroy', $post))
            ->assertOk();

        $this->assertDatabaseMissing('posts', $post->toArray());
    }
}