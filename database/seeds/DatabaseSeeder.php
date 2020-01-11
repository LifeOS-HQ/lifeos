<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Daniel',
            'email' => 'daniel@hof-sundermeier.de',
            'email_verified_at' => now(),
            'password' => Hash::make('Flash3flash'),
            'api_token' => Str::random(80),
        ]);
        // $this->call(UsersTableSeeder::class);

        Artisan::call('work:time:import');
    }
}
