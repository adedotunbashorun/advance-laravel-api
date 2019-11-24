<?php

namespace Modules\Roles\Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement("SET FOREIGN_KEY_CHECKS = 0");
        \DB::table("roles")->truncate();
        $role = Role::create(['name' => 'administrator']);
        $role->givePermissionTo(['users.index','users.create','users.update','users.show','users.store','users.destroy','roles.index','roles.create','roles.update','roles.show','roles.store','roles.destroy','settings.index','settings.store']);
        $role = Role::create(['name' => 'customer']);
        $role = Role::create(['name' => 'moderator']);
    }
}
