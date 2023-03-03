<?php

namespace Database\Factories\Helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FactoryHelper
{
  /**
   * This function will get a random model id from database.
   * @param string|HasFactory $model
   */
  public static function getRandomModelId(string $model)
  {
    // get post count
    $count = $model::query()->count();

    if ( $count === 0 ) {
      // create a new record and retrieve the record id
      return $model::factory()->create()->id;
    } else {
      // generate random number between 1 and model count
      return rand(1, $count);
    }
  }
}