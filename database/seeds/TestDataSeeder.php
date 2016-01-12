<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TestDataSeeder extends Seeder
{
    /**
     * Seed the database with test (non-production) data.
     *
     * @return void
     */
    public function run()
    {
        $createdTime = date('Y-m-d H:i:s');
        $faker = Faker::create();

        Model::unguard();

        // Disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables here
        DB::table('user_logins')->truncate();
        DB::table('users')->truncate();

        // Place creation statements here
        $this->createUser('admin', 'admin@test.com', 'admin');
        $this->createUser('user', 'user@test.com', 'user');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Model::reguard();
    }

    protected function createUser($type, $email, $password = null)
    {
        $createdTime = date('Y-m-d H:i:s');
        if (is_null($password)) {
            $password = $email;
        }

        $userId = DB::table('users')->insertGetId([
            'email' => $email,
            'password' => bcrypt($password),
            'status' => 'active',
            'type' => $type,
            'created_at' => $createdTime,
            'updated_at' => $createdTime,
        ]);

        return $userId;
    }
}
