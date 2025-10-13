<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('category_item')->truncate();
        DB::table('items')->truncate();
        DB::table('categories')->truncate();
        DB::table('conditions')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            CategorySeeder::class,
            ConditionsTableSeeder::class,
            ItemsTableSeeder::class,
            CategoryItemSeeder::class, 
        ]);
    }
}
