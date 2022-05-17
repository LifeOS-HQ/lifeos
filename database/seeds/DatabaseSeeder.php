<?php

use App\Models\Services\Service;
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
            'name' => 'User',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'api_token' => Str::random(80),
        ]);
        // $this->call(UsersTableSeeder::class);

        // Artisan::call('work:time:import');

        Service::create([
            'slug' => 'exist',
            'name' => 'exist.io',
            'type' => 'oauth',
        ]);

        Service::create([
            'slug' => 'rentablo',
            'name' => 'Rentablo',
            'type' => 'password',
        ]);
    }
}
