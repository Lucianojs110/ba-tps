<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
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
        $user = new User();
        $user->name = 'Administrador';
        $user->last_name = 'Admin';
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('admin123');
        $user->save();
        
        
        $role1 = New Role();
        $role1->name = 'Administrador';
        $role1->save();

        $role2 = New Role();
        $role2->name = 'Pyme';
        $role2->save();

        $role3 = New Role();
        $role3->name = 'EAE';
        $role3->save();

        $roleuser = New RoleUser();
        $roleuser->user_id = 1;
        $roleuser->role_id = 1;
        $roleuser->save();
    }
}
