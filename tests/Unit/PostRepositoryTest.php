<?php

namespace Tests\Unit;

use App\Exceptions\GeneralJsonException;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;
    private $post;

    public function setUp() : void
    {
        parent::setUp();

        $this->repository = $this->app->make(PostRepository::class);
        $this->post = Post::factory(1)->create()->first();
    }

    public function test_create()
    {
        $payload = [
            'title' => 'heyaa',
            'body' => []
        ];

        $result = $this->repository->create($payload);

        $this->assertSame($payload['title'], $result->title, 'Post created does not have the same title.');
    }

    public function test_update()
    {
        $payload = [
            'title' => 'heya123',
        ];

        $updated = $this->repository->update($this->post, $payload);
        $this->assertSame($payload['title'], $updated->title, 'Post updated does not have the same title.');
    }

    public function test_delete_will_throw_exception_when_delete_post_that_does_not_exists()
    {
        $post = Post::factory(1)->make()->first();

        $this->expectException(GeneralJsonException::class);
        $this->repository->forceDelete($post);
    }

    public function test_delete()
    {
        $this->repository->forceDelete($this->post);
        $found = Post::query()->find($this->post->id);

        $this->assertSame(null, $found, 'Post is not deleted.');
    }
}