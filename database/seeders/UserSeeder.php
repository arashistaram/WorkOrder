<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'          => 'اقای بهرنگ',
                'username'      => 'behrang',
                'password'      => Hash::make('123456789'),
                'role'          => 'admin',
                'phone'         => '09120000001',
                'employee_code' => 'EMP-1001',
                'job_title'     => 'مدیر عامل',
                'is_active'     => true,
                'settings'      => [
                    'theme'    => 'dark',
                ],
            ],
            [
                'name'          => 'اقای جمشیدی',
                'username'      => 'jamshidi',
                'password'      => Hash::make('123456789'),
                'role'          => 'manager',
                'phone'         => '09120000002',
                'employee_code' => 'EMP-1002',
                'job_title'     => 'مدیر آیتی',
                'is_active'     => true,
                'settings'      => [
                    'theme'    => 'light',
                ],
            ],

            [
                'name'          => 'آرش نریمانی',
                'username'      => 'arash',
                'password'      => Hash::make('123456789'),
                'role'          => 'user',
                'phone'         => '09120000002',
                'employee_code' => 'EMP-1003',
                'job_title'     => 'برنامه نویس',
                'is_active'     => true,
                'settings'      => [
                    'theme'    => 'dark',
                ],
            ],
            [
                'name'          => 'آرمین آقازاده',
                'username'      => 'armin',
                'password'      => Hash::make('123456789'),
                'role'          => 'user',
                'phone'         => '09120000003',
                'employee_code' => 'EMP-1004',
                'job_title'     => 'شبکه کار',
                'is_active'     => true,
                'settings'      => [
                    'theme'    => 'light',
                ],
            ],
            [
                'name'          => 'امین منصور فر',
                'username'      => 'amin',
                'password'      => Hash::make('123456789'),
                'role'          => 'user',
                'phone'         => '09120000004',
                'employee_code' => 'EMP-1005',
                'job_title'     => 'دیتا آنالیست',
                'is_active'     => true,
                'settings'      => null,
            ],
            [
                'name'          => 'احمد شیرین زاده',
                'username'      => 'ahmad',
                'password'      => Hash::make('123456789'),
                'role'          => 'user',
                'phone'         => '09120000005',
                'employee_code' => 'EMP-1006',
                'job_title'     => 'سخت افزار',
                'is_active'     => false,
                'settings'      => null,
            ],
        ];

        foreach ($users as $userData) {
            User::query()->updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }

        $this->command->info('✅ کاربران پیش‌فرض با موفقیت ساخته شدند.');
    }
}
