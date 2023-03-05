<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentRepository extends BaseRepository
{

  /**
   * @param array $attributes
   * @return mixed
   */
  public function create(array $attributes)
  {
    return DB::transaction(function () use ($attributes) {
      $created = Comment::query()->create([
        'body' => data_get($attributes, 'body'),
        'user_id' => data_get($attributes, 'user_id'),
        'post_id' => data_get($attributes, 'post_id'),
      ]);

      throw_if(! $created, \Exception::class, 'Failed to create comment.');

      return $created;
    });
  }

  /**
   *
   * @param Comment $comment
   * @param array $attributes
   * @return mixed
   */
  public function update($comment, array $attributes)
  {
    return DB::transaction(function () use ($comment, $attributes) {
      $updated = $comment->update([
        'body' => data_get($attributes, 'body', $comment->body),
        'user_id' => data_get($attributes, 'user_id', $comment->user_id),
        'post_id' => data_get($attributes, 'post_id', $comment->post_id),
      ]);

      throw_if(! $updated, \Exception::class, 'Failed to update comment.');

      return $comment;
    });
  }

  /**
   *
   * @param Comment $comment
   * @return mixed
   */
  public function forceDelete($comment)
  {
    return DB::transaction(function () use ($comment) {
      $deleted = $comment->forceDelete();
      throw_if(! $deleted, \Exception::class, 'Failed to delete comment.');

      return $deleted;
    });
  }
}