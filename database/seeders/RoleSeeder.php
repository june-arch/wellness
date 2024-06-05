<?php

namespace Database\Seeders;

use App\Models\Users\Permission;
use App\Models\Users\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = config('role');
        $maps  = config('permission.map');

        $permissions = Permission::get();

        foreach ($roles as $role => $data) {
            $createdRole = Role::create([
                'name' => ucfirst($role),
            ]);

            $gates = collect([]);

            foreach ($data['permissions'] as $gate => $actions) {
                foreach (str_split($actions) as $map) {
                    $gates->push(strtoupper($maps[$map] . '_' . $gate));
                }
            }

            $synced = $permissions->whereIn('gate', $gates);

            $createdRole->permissions()->sync($synced->pluck('id'));
        }
    }
}
