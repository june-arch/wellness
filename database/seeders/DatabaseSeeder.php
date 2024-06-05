<?php

namespace Database\Seeders;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            SuperAdminSeeder::class,
            CompanySeeder::class,
            TaskSeeder::class,
        ]);
    }
}
