<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        $password = $this->argument('password');

        // Check if user already exists
        $existingUser = User::where('username', $username)->first();
        if ($existingUser) {
            if ($existingUser->is_admin) {
                $this->error("User '{$username}' already exists and is already an admin.");
                return 1;
            }
            
            // Update existing user to admin
            $existingUser->is_admin = true;
            $existingUser->is_staff = true;
            $existingUser->save();
            
            $this->info("User '{$username}' has been granted admin privileges.");
            return 0;
        }

        // Create new admin user
        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'username' => $username,
            'passphrase_hash' => Hash::make($password),
            'registration_date' => now(),
            'invite_code' => (string) Str::uuid(),
            'is_admin' => true,
            'is_staff' => true,
            'is_seller' => false,
        ]);

        $this->info("Admin user '{$username}' created successfully!");
        $this->info("Login with username: {$username}");
        
        return 0;
    }
}
