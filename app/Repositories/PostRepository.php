<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository
{

  /**
   * @param array $attributes
   * @return mixed
   */
  public function create(array $attributes)
  {
    return DB::transaction(function () use ($attributes) {
      $created = Post::query()->create([
        'title' => data_get($attributes, 'title', 'untitled'),
        'body' => data_get($attributes, 'body')
      ]);

      throw_if(! $created, \Exception::class, 'Failed to create post.');

      if ( $userIds = data_get($attributes, 'user_ids') ) {
        $created->users()->sync($userIds);
      }

      return $created;
    });
  }

  /**
   *
   * @param Post $post
   * @param array $attributes
   * @return mixed
   */
  public function update($post, array $attributes)
  {
    return DB::transaction(function () use ($post, $attributes) {
      $updated = $post->update([
        'title' => data_get($attributes, 'title', $post->title),
        'body' => data_get($attributes, 'body', $post->body),
      ]);

      throw_if(! $updated, \Exception::class, 'Failed to update post.');

      if ( $userIds = data_get($attributes, 'user_ids') ) {
        $post->users()->sync($userIds);
      }

      return $post;
    });
  }

  /**
   *
   * @param Post $post
   * @return mixed
   */
  public function forceDelete($post)
  {
    return DB::transaction(function () use ($post) {
      $deleted = $post->forceDelete();
      throw_if(! $deleted, \Exception::class, 'Could not delete post.');

      return $deleted;
    });
  }
}