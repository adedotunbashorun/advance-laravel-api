<?php


namespace Modules\Roles\Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Modules\Users\Services\PermissionManager;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = app(PermissionManager::class)->all();
        $all_permissions = [];
        foreach ($permissions as $value){
            foreach ($value as $value_name => $key){
                foreach ($key as $action){
                    $all_permissions[] = $value_name.'.'.$action;
                }
            }
        }
        \DB::statement("SET FOREIGN_KEY_CHECKS = 0");
        \DB::table("permissions")->truncate();
        foreach ($all_permissions as $permission) {
            Permission::create(['name' => $permission]);
       }
    }
}
