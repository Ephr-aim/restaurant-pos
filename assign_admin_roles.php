<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$user = User::where('email', 'admin@bulandacanteen.com')->first();

if (!$user) {
    echo "Admin user not found.\n";
    exit;
}

// Get or create Admin role
$adminRole = Role::firstOrCreate(['name' => 'Admin']);

// Assign all permissions to Admin role
$permissions = Permission::all();
$adminRole->syncPermissions($permissions);

// Assign Admin role to user
$user->assignRole($adminRole);

echo "Admin role assigned with all permissions to {$user->email}.\n";
