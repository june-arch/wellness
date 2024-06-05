<?php

namespace Database\Seeders;

use App\Models\Users\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = config('permission.data');
        $maps        = config('permission.map');

        foreach ($permissions as $gate => $actions) {
            foreach (str_split($actions) as $map) {
                Permission::updateOrCreate(['gate' => strtoupper($maps[$map] . '_' . $gate)]);
            }
        }
    }
}
