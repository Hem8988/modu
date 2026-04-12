<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Only insert admin if no users exist
        if (DB::table('users')->count() === 0) {
            DB::table('users')->insert([
                'name'       => 'Leo Admin',
                'email'      => 'admin@modushade.com',
                'username'   => 'admin',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Update existing admin to have hashed password if plain
            $user = DB::table('users')->where('username','admin')->first();
            if ($user && $user->password === 'admin123') {
                DB::table('users')->where('username','admin')->update(['password' => Hash::make('admin123')]);
                echo "Password hashed for existing admin.\n";
            }
        }
    }
}
