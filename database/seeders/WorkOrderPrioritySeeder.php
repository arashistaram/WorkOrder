<?php

namespace Database\Seeders;

use App\Models\WorkOrderPriority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkOrderPrioritySeeder extends Seeder
{
    public function run(): void
    {
        $priorities = [
            ['key' => 'low',      'label' => 'کم',      'color' => 'gray',   'level' => 1],
            ['key' => 'medium',   'label' => 'متوسط',   'color' => 'blue',   'level' => 2],
            ['key' => 'high',     'label' => 'بالا',    'color' => 'yellow', 'level' => 3],
            ['key' => 'critical', 'label' => 'بحرانی',  'color' => 'red',    'level' => 4],
        ];

        foreach ($priorities as $data) {
            WorkOrderPriority::query()->updateOrCreate(
                ['key' => $data['key']],
                $data + ['is_active' => true]
            );
        }

        $this->command->info('✅ اولویت‌های سفارش کار ساخته شدند.');
    }
}
