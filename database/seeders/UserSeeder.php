<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Traits\SetForeignKeysCheck;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use TruncateTable, SetForeignKeysCheck;

    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        $this->disableForeignKeys();
        $this->truncate('users');
        $users = User::factory(10)->create();
        $this->enableForeignKeys();
    }
}