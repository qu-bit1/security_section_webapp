<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'super-admin',
            'user'
        ];
        $permissions = [
            'admin access'=> ['super-admin'],
            'create reports'=> ['super-admin', 'user'],
            'edit all reports'=> ['super-admin'],
            'edit own reports'=> ['super-admin','user'],
            'delete own reports'=> ['super-admin','user'],
            'delete all reports'=> ['super-admin'],
            'access own reports'=> ['super-admin', 'user'],
            'access all reports'=> ['super-admin'],
            'access permissions'=> ['super-admin'],
            'assign permissions'=> ['super-admin'],
            'access roles'=> ['super-admin'],
            'create roles'=> ['super-admin'],
            'edit roles'=> ['super-admin'],
            'delete roles'=> ['super-admin'],
            'assign roles'=> ['super-admin'],
            'access role permissions'=> ['super-admin'],
            'access user roles'=> ['super-admin'],
            'access users'=> ['super-admin'],
            'edit users'=> ['super-admin'],
            'delete users'=> ['super-admin'],
            'access all remarks'=> ['super-admin'],
            'access own remarks'=> ['super-admin'],
            'create remarks'=> ['super-admin'],
            'edit all remarks'=> ['super-admin'],
            'edit own remarks'=> ['super-admin'],
            'delete all remarks'=> ['super-admin'],
            'delete own remarks'=> ['super-admin'],
            'approve reports'=> ['super-admin'],
        ];
        //create roles
        foreach ($roles as $role) {
            $rolesArray[$role] = Role::create(['name' => $role]);
        }
        //create permissions
        foreach ($permissions as $permission => $authorized_roles) {
            //create permission
            Permission::create(['name' => $permission]);
            //authorize roles to those permissions
            foreach ($authorized_roles as $role) {
                $rolesArray[$role]->givePermissionTo($permission);
            }
        }
    }
}
