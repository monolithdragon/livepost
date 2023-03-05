<?php

namespace Tests\Unit;

use App\Exceptions\GeneralJsonException;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Repositories\CommentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentRepositoryTest extends TestCase
{
    use RefreshDatabase;
    private $repository;
    private $comment;

    public function setUp() : void
    {
        parent::setUp();

        $this->repository = $this->app->make(CommentRepository::class);
        $this->comment = Comment::factory(1)->create()->first();
    }

    public function test_create()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $payload = [
            'body' => ['something'],
            'user_id' => $user->id,
            'post_id' => $post->id,
        ];

        $result = $this->repository->create($payload);

        $this->assertSame($payload['body'], $result->body, 'Comment created does not have the same title.');
    }

    public function test_update()
    {
        $payload = [
            'body' => ['abc123'],
        ];

        $updated = $this->repository->update($this->comment, $payload);
        $this->assertSame($payload['body'], $updated->body, 'Comment updated does not have the same title.');
    }

    public function test_delete_will_throw_exception_when_delete_comment_that_does_not_exists()
    {
        $comment = Comment::factory(1)->make()->first();

        $this->expectException(GeneralJsonException::class);
        $this->repository->forceDelete($comment);
    }

    public function test_delete()
    {
        $this->repository->forceDelete($this->comment);
        $found = Comment::query()->find($this->comment->id);

        $this->assertSame(null, $found, 'Comment is not deleted.');
    }
}