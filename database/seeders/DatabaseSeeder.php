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
       
    
        $role1 = New Role();
        $role1->name = 'Administrador';
        $role1->save();

        $role2 = New Role();
        $role2->name = 'Usuario';
        $role2->save();

        $user = new User();
        $user->name = 'Administrador';
        $user->last_name = 'Admin';
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('admin123');
        $user->id_role = 1;
        $user->save();

       

       
    }
}
