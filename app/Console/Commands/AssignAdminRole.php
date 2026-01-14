<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignAdminRole extends Command
{
    protected $signature = 'admin:assign-role';
    protected $description = 'Assign all permissions to Admin user';

    public function handle()
    {
        $user = User::where('email', 'admin@bulandacanteen.com')->first();

        if (!$user) {
            $this->error('Admin user not found.');
            return 1;
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());
        $user->assignRole($adminRole);

        $this->info("Admin role assigned with all permissions to {$user->email}");
        return 0;
    }
}

