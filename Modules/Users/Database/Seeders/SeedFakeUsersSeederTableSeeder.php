<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\User;
use Modules\Users\Services\PermissionManager;

class SeedFakeUsersSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        $user = User::firstOrCreate([
            'first_name' => 'Yinka',
            'last_name' => 'Admin',
            'email' => 'admin@pennylender.com',
            'password' => 'admin',
            'is_active' => 1,
            'email_verified_at' =>  date('Y-m-d H:i:s')
        ]);
        $user->assignRole('administrator');
        $permissions = app(PermissionManager::class)->all();
        $all_permissions = [];
        foreach ($permissions as $value){
            foreach ($value as $value_name => $key){
                foreach ($key as $action){
                    $all_permissions[] = $value_name.'.'.$action;
                }
            }
        }
        foreach ($all_permissions as $permission) {
            $user->givePermissionTo($permission);
        }
    }
}
