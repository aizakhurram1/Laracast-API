<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $users = User::factory(10)->create();

        Ticket::factory(10)->make()->each(function ($ticket) use ($users) {
            $ticket->user_id = $users->random()->id;
            $ticket->save();
        });

        \App\Models\User::create([

            'name' => 'manager',
            'email' => 'manager@manager.com',
            'password' => bcrypt('password'),
            'is_manager' => true,

        ]);
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
