<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RealDataSeeder extends Seeder
{
    /**
     * Seed the database with real (production) data.
     *
     * @return void
     */
    public function run()
    {
        $createdTime = date('Y-m-d H:i:s');
        $faker = Faker::create();

        Model::unguard();

        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables here

        // Place creation statements here

        // Re-enable foreign key checks`
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Model::reguard();
    }
}
