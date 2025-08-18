<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignParentRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-parent-role {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign parent role to a user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }
        
        $parentRole = Role::where('name', 'parent')->first();
        
        if (!$parentRole) {
            $this->error("Parent role not found! Please run the RoleSeeder first.");
            return 1;
        }
        
        $user->assignRole('parent');
        
        $this->info("Parent role assigned successfully to user: {$email}");
        
        return 0;
    }
}
