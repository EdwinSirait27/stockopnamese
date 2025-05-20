<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   $Admin = Role::create(['name' => 'Admin']);
    $Penginput = Role::create(['name' => 'Penginput']);
    $Penghitung = Role::create(['name' => 'Penghitung']);
    $permissions = ['create role', 'edit role', 'delete role', 'view role'];
    foreach ($permissions as $perm) {
        Permission::create(['name' => $perm]);
    }
    $Admin->givePermissionTo(Permission::all());
    }
    
}
