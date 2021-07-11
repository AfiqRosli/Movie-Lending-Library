<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LendsTableSeeder::class);
        $this->call(MoviesTableSeeder::class);
        $this->call(MembersTableSeeder::class);
    }
}
