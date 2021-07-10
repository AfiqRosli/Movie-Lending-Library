<?php

use Illuminate\Database\Seeder;

class LendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Lend::class, 50)->create();
    }
}
