<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait SetForeignKeysCheck
{
  protected function enableForeignKeys()
  {
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
  }

  protected function disableForeignKeys()
  {
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
  }
}