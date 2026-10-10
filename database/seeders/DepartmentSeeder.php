<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    protected array $departments = [
        [
            'code'        => 'MGMT',
            'name'        => 'مدیریت',
            'description' => 'مدیریت ارشد و راهبری سازمان',
            'color'       => '#1f2937',
            'phone'       => '021-91000000',
            'location'    => 'ساختمان مرکزی - طبقه ۵',
            'parent'      => null,
        ],
        [
            'code'        => 'IT',
            'name'        => 'فناوری اطلاعات',
            'description' => 'زیرساخت، شبکه و سیستم‌های نرم‌افزاری',
            'color'       => '#2b52d6',
            'phone'       => '021-91000100',
            'location'    => 'ساختمان مرکزی - طبقه ۳',
            'parent'      => null,
        ],
        [
            'code'        => 'HR',
            'name'        => 'منابع انسانی',
            'description' => 'امور کارکنان، استخدام و رفاه',
            'color'       => '#16a34a',
            'phone'       => '021-91000200',
            'location'    => 'ساختمان مرکزی - طبقه ۲',
            'parent'      => null,
        ],
        [
            'code'        => 'FIN',
            'name'        => 'مالی و حسابداری',
            'description' => 'امور مالی، بودجه و حسابداری',
            'color'       => '#ca8a04',
            'phone'       => '021-91000300',
            'location'    => 'ساختمان مرکزی - طبقه ۲',
            'parent'      => null,
        ],
        [
            'code'        => 'PROD',
            'name'        => 'تولید',
            'description' => 'خط تولید و عملیات ساخت',
            'color'       => '#dc2626',
            'phone'       => '021-91000400',
            'location'    => 'سالن تولید - سالن A',
            'parent'      => null,
        ],
        [
            'code'        => 'SALES',
            'name'        => 'فروش و بازاریابی',
            'description' => 'فروش، بازاریابی و ارتباط با مشتری',
            'color'       => '#9333ea',
            'phone'       => '021-91000500',
            'location'    => 'ساختمان مرکزی - طبقه ۱',
            'parent'      => null,
        ],

        [
            'code'        => 'IT-DEV',
            'name'        => 'توسعه نرم‌افزار',
            'description' => 'طراحی و توسعه نرم‌افزار',
            'color'       => '#3b82f6',
            'phone'       => '021-91000101',
            'location'    => 'ساختمان مرکزی - طبقه ۳',
            'parent'      => 'IT',
        ],
        [
            'code'        => 'IT-NET',
            'name'        => 'شبکه و زیرساخت',
            'description' => 'نگهداری شبکه، سرورها و زیرساخت',
            'color'       => '#3b82f6',
            'phone'       => '021-91000102',
            'location'    => 'ساختمان مرکزی - طبقه ۳',
            'parent'      => 'IT',
        ],
        [
            'code'        => 'IT-SEC',
            'name'        => 'امنیت اطلاعات',
            'description' => 'امنیت سایبری و مدیریت دسترسی‌ها',
            'color'       => '#3b82f6',
            'phone'       => '021-91000103',
            'location'    => 'ساختمان مرکزی - طبقه ۳',
            'parent'      => 'IT',
        ],
        [
            'code'        => 'PROD-QC',
            'name'        => 'کنترل کیفیت',
            'description' => 'بازرسی و تضمین کیفیت محصول',
            'color'       => '#f97316',
            'phone'       => '021-91000401',
            'location'    => 'سالن تولید - سالن B',
            'parent'      => 'PROD',
        ],
        [
            'code'        => 'PROD-MAINT',
            'name'        => 'نگهداری و تعمیرات',
            'description' => 'نگهداری پیشگیرانه و تعمیر تجهیزات',
            'color'       => '#f97316',
            'phone'       => '021-91000402',
            'location'    => 'سالن تولید - کارگاه',
            'parent'      => 'PROD',
        ],
        [
            'code'        => 'FIN-ACC',
            'name'        => 'حسابداری',
            'description' => 'حسابداری مالی و بهای تمام‌شده',
            'color'       => '#eab308',
            'phone'       => '021-91000301',
            'location'    => 'ساختمان مرکزی - طبقه ۲',
            'parent'      => 'FIN',
        ],
        [
            'code'        => 'FIN-PROC',
            'name'        => 'خرید و تدارکات',
            'description' => 'تأمین کالا و خدمات',
            'color'       => '#eab308',
            'phone'       => '021-91000302',
            'location'    => 'ساختمان مرکزی - طبقه ۲',
            'parent'      => 'FIN',
        ],
        [
            'code'        => 'SUP',
            'name'        => 'پشتیبانی فنی',
            'description' => 'پشتیبانی فنی و خدمات پس از فروش',
            'color'       => '#0891b2',
            'phone'       => '021-91000600',
            'location'    => 'ساختمان مرکزی - طبقه ۱',
            'parent'      => 'SALES',
        ],
    ];

    public function run(): void
    {
        $now = now();


        foreach ($this->departments as $dept) {
            if ($dept['parent'] !== null) {
                continue;
            }
            $this->upsertDepartment($dept, null, $now);
        }

        foreach ($this->departments as $dept) {
            if ($dept['parent'] === null) {
                continue;
            }

            $parentId = DB::table('departments')
                ->where('code', $dept['parent'])
                ->value('id');

            if (! $parentId) {
                $this->command->warn("⚠️  والد با کد {$dept['parent']} پیدا نشد؛ {$dept['code']} رد شد.");
                continue;
            }

            $this->upsertDepartment($dept, $parentId, $now);
        }

        $count = DB::table('departments')->whereNull('deleted_at')->count();
        $this->command->info("✅ DepartmentSeeder: {$count} دپارتمان در سیستم ثبت شده است.");
    }

    protected function upsertDepartment(array $dept, ?int $parentId, $now): void
    {
        DB::table('departments')->updateOrInsert(
            ['code' => $dept['code']],
            [
                'name'        => $dept['name'],
                'description' => $dept['description'] ?? null,
                'color'       => $dept['color']      ?? '#2b52d6',
                'phone'       => $dept['phone']      ?? null,
                'location'    => $dept['location']   ?? null,
                'parent_id'   => $parentId,
                'is_active'   => true,
                'updated_at'  => $now,
                'created_at'  => $now,
            ]
        );
    }
}
