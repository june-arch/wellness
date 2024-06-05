<?php

namespace Database\Seeders;

use App\Models\Companies\Company;
use App\Models\Companies\HealthThreshold;
use App\Models\Users\Admin;
use App\Models\Users\Member;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Repositories\CompanyRepository;
use Database\Seeders\Seeder;

class CompanySeeder extends Seeder
{
    public function run()
    {
        if (Company::count() === 0) {
            $company = Company::create([
                'name'        => 'PT Bangun Nusa Sejahtera',
                'description' => "Perusahaan yang bergerak dibidang penjualan produk produk teknologi untuk bisnis.",
                'phone'       => '088888888888',
                'email'       => 'office@bangunnusa.com',
                'address'     => 'Jl. Gatot Subroto kav. 16 No. 84, Jakarta Selatan',
                'expires_at'  => now()->add('year', 1),
            ]);

            $this->addThumbnail($company);
            $this->insertHealthThreshold($company);

            $company = Company::where('phone', '088888888888')->with('healthThresholds')->first();

            $employeeRoles = Role::whereIn('name', ['Admin', 'Master', 'Supervisor'])->get();

            foreach ($employeeRoles as $role) {
                $count = $role->name === 'Supervisor' ? 10 : 2;
                for ($o = 1; $o <= $count; $o++) {
                    $admin = $role->admins()->create(
                        array_merge(
                            User::factory()->make()->toArray(),
                            [
                                'email'             => strtolower($role->name) . "{$o}{$company->id}@gmail.com",
                                'company_id'        => $company->id,
                                'role_id'           => $role->id,
                                'password'          => 'password',
                                'email_verified_at' => now(),
                                'birthdate'         => now()->subHours(rand(140160, 438000))->format('Y-m-d H:i:s'),
                                'email_verified_at' => now(),
                            ]
                        )
                    );

                    $this->addThumbnail($admin);
                }
            }

            $supervisors = Admin::whereHas('role', function ($q) {$q->where('name', 'Supervisor');})->get();

            $thresholds = $company->fresh()->healthThresholds;

            foreach ($supervisors as $supervisor) {
                for ($i = 0; $i <= rand(10, 30); $i++) {
                    $member = $role->members()->create(
                        array_merge(
                            Member::factory()->make()->toArray(),
                            [
                                'email'             => "member{$i}{$supervisor->id}{$company->id}@gmail.com",
                                'company_id'        => $company->id,
                                'password'          => 'password',
                                'email_verified_at' => now(),
                                'birthdate'         => now()->subHours(rand(140160, 438000))->format('Y-m-d H:i:s'),
                                'email_verified_at' => now(),
                                'parent_id'         => $supervisor->id,
                            ]
                        )
                    );

                    $this->addThumbnail($member);

                    $this->addHealthData($member, $thresholds);
                }
            }
        }
    }

    private function addHealthData(Member $member, $thresholds)
    {

        $date = now();

        $bloodPresures = collect([
            [90, 60],
            [100, 60],
            [110, 60],

            [90, 70],
            [100, 70],
            [110, 70],
            [120, 70],

            [100, 80],
            [110, 80],
            [120, 80],
            [130, 80],
            [140, 80],

            [120, 90],
            [130, 90],
            [140, 90],
            [150, 90],
            [160, 90],

            [120, 100],
            [130, 100],
            [140, 100],
            [150, 100],
            [160, 100],
            [170, 100],
        ]);

        for ($i = 60; $i >= 0; $i--) {
            $now = $i ? $date->subDays($i) : now();

            $data = collect([
                'company_id' => $member->company_id,
                'date'       => $now->format('Y-m-d'),
            ]);

            $data->put('step', rand(200, 10_000));
            $data->put('workout_duration', rand(3, 60));
            $data->put('height', rand(150, 170));
            $data->put('weight', rand(40, 100));
            $data->put('waist_size', rand(60, 120));
            $data->put('hydration', rand(1, 8));
            $data->put('sleep_duration', rand(3, 8));
            $data->put('stress_level', rand(10, 80));
            $data->put('master_point', rand(60, 100));
            $data->put('systolic', $bloodPresures->random()[0]);
            $data->put('diastolic', $bloodPresures->random()[1]);
            $data->put('blood_glucose', collect([190, 200, 220, 230])->random());
            $data->put('cholesterol', collect([290, 300, 320, 330])->random());

            $member->healths()->create($data->toArray());
        }
    }

    private function insertHealthThreshold(Company $company)
    {
        $data = CompanyRepository::defaultThresholds($company);
        HealthThreshold::insert($data->toArray());
    }
}
