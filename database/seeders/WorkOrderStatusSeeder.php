<?php

namespace Database\Seeders;

use App\Models\WorkOrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkOrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['key' => 'draft',       'label' => 'پیش‌نویس',         'color' => 'gray',   'is_final' => false],
            ['key' => 'pending',     'label' => 'در انتظار تخصیص', 'color' => 'yellow', 'is_final' => false],
            ['key' => 'assigned',    'label' => 'تخصیص داده شده',  'color' => 'blue',   'is_final' => false],
            ['key' => 'in_progress', 'label' => 'در حال انجام',    'color' => 'purple', 'is_final' => false],
            ['key' => 'completed',   'label' => 'تکمیل شده',       'color' => 'green',  'is_final' => true],
            ['key' => 'cancelled',   'label' => 'لغو شده',         'color' => 'red',    'is_final' => true],
        ];

        foreach ($statuses as $data) {
            WorkOrderStatus::query()->updateOrCreate(
                ['key' => $data['key']],
                $data + ['is_active' => true]
            );
        }

        $this->command->info('✅ وضعیت‌های سفارش کار ساخته شدند.');
    }
}
