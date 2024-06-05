<?php

namespace Database\Seeders;

use App\Models\Users\Admin;
use App\Models\Users\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        if (Admin::whereEmail('superadmin@gmail.com')->count() === 0) {
            $userId = 1;

            $data = [
                'created_by_id' => $userId,
                'updated_by_id' => $userId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            Admin::insert(array_merge(
                $data,
                [
                    'id'                => $userId,
                    'name'              => 'Super Admin',
                    'email'             => 'superadmin@gmail.com',
                    'phone'             => '085787587',
                    'password'          => bcrypt('password'),
                    'role_id'           => Role::whereName('Superadmin')->first()->id,
                    'type'              => Admin::class,
                    'email_verified_at' => now(),
                ]
            ));

            $admin = Admin::where('email', 'superadmin@gmail.com')->first();
            $this->addThumbnail($admin);
        }
    }
}
