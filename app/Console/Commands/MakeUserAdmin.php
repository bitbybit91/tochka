<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant admin privileges to an existing user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');

        $user = User::where('username', $username)->first();

        if (!$user) {
            $this->error("User '{$username}' not found.");
            $this->info("Create a new admin user with: php artisan user:create-admin {$username} password");
            return 1;
        }

        if ($user->is_admin) {
            $this->info("User '{$username}' is already an admin.");
            return 0;
        }

        $user->is_admin = true;
        $user->is_staff = true;
        $user->save();

        $this->info("User '{$username}' has been granted admin privileges.");
        
        return 0;
    }
}
