<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderChecklistItem;
use App\Models\WorkOrderPriority;
use App\Models\WorkOrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkOrderSeeder extends Seeder
{

    /**
     * @throws \Throwable
     */
    public function run(): void
    {
        $users       = User::query()->where('is_active', true)->get();
        $departments = Department::query()->get();
        $statuses    = WorkOrderStatus::query()->get()->keyBy('key');
        $priorities  = WorkOrderPriority::query()->get()->keyBy('key');

        if ($users->isEmpty() || $departments->isEmpty()) {
            $this->command->error('❌ ابتدا UserSeeder و DepartmentSeeder را اجرا کنید.');
            return;
        }

        if ($statuses->isEmpty() || $priorities->isEmpty()) {
            $this->command->error('❌ ابتدا WorkOrderStatusSeeder و WorkOrderPrioritySeeder را اجرا کنید.');
            return;
        }

        $titles = [
            'تعمیر پرینتر طبقه دوم',
            'نصب نرم‌افزار حسابداری',
            'راه‌اندازی شبکه اتاق جلسات',
            'تعویض هارد سرور اصلی',
            'بروزرسانی سیستم عامل کارکنان',
            'رفع مشکل اینترنت واحد فروش',
            'نصب دوربین مداربسته انبار',
            'پشتیبان‌گیری از دیتابیس',
            'خرید و نصب رم جدید',
            'بررسی کندی سیستم حسابداری',
            'راه‌اندازی ایمیل سازمانی',
            'تنظیم فایروال جدید',
            'نصب آنتی‌ویروس روی لپ‌تاپ‌ها',
            'تعمیر کیبورد و ماوس',
            'راه‌اندازی VPN برای دورکاری',
            'انتقال داده‌ها به سرور جدید',
            'آموزش نرم‌افزار به کارکنان',
            'پیگیری خطای گزارش‌گیری',
            'بهینه‌سازی سرعت وب‌سایت',
            'نصب اسکنر جدید',
            'تعویض کابل شبکه',
            'بازیابی فایل حذف شده',
            'بررسی هشدار امنیتی',
            'نصب سیستم عامل جدید',
            'تنظیم پرینتر شبکه‌ای',
            'رفع مشکل صدا در سیستم',
            'بروزرسانی آنتی‌ویروس سرور',
            'راه‌اندازی سیستم حضور و غیاب',
            'تعمیر مانیتور واحد مالی',
            'بررسی خطای برنامه انبار',
        ];

        $descriptions = [
            'کاربر گزارش داده که سیستم به‌درستی کار نمی‌کند و نیاز به بررسی فوری دارد.',
            'این درخواست از طرف واحد مربوطه ارسال شده و طبق روال باید بررسی شود.',
            'مشکل از دیروز به وجود آمده و کاربران متعددی تحت تأثیر قرار گرفته‌اند.',
            'نیاز به بررسی و اقدام سریع داریم، لطفاً در اولویت قرار بگیرد.',
            'طبق چک‌لیست دوره‌ای باید انجام شود.',
            'کاربر جدید نیاز به راه‌اندازی سیستم دارد.',
            'پس از بروزرسانی اخیر این مشکل به وجود آمده است.',
            'درخواست ارتقاء سخت‌افزاری برای بهبود عملکرد.',
            null, // بعضی‌ها بدون توضیحات
            null,
        ];

        $checklistTemplates = [
            ['بررسی اولیه مشکل', 'تهیه تجهیزات لازم', 'انجام تعمیرات', 'تست نهایی', 'تحویل به کاربر'],
            ['جمع‌آوری اطلاعات', 'تهیه نسخه پشتیبان', 'اجرای تغییرات', 'اعتبارسنجی', 'مستندسازی'],
            ['بررسی سخت‌افزار', 'بررسی نرم‌افزار', 'تعویض قطعه معیوب', 'راه‌اندازی مجدد'],
            ['تماس با کاربر', 'بررسی از راه دور', 'حضور در محل', 'رفع مشکل'],
            ['تشخیص مشکل', 'رفع مشکل', 'اطلاع به کاربر'],
        ];

        $this->command->info('⏳ در حال ساخت سفارش‌های کار...');

        $totalToCreate = 60;
        $statusKeys    = ['draft', 'pending', 'pending', 'assigned', 'assigned', 'in_progress', 'in_progress', 'completed', 'completed', 'cancelled'];
        $priorityKeys  = ['low', 'medium', 'medium', 'high', 'high', 'critical'];

        DB::transaction(function () use (
            $users, $departments, $statuses, $priorities,
            $titles, $descriptions, $checklistTemplates,
            $totalToCreate, $statusKeys, $priorityKeys
        ) {
            for ($i = 1; $i <= $totalToCreate; $i++) {

                $statusKey   = $statusKeys[array_rand($statusKeys)];
                $priorityKey = $priorityKeys[array_rand($priorityKeys)];

                $status   = $statuses[$statusKey];
                $priority = $priorities[$priorityKey];

                $department = $departments->random();
                $creator    = $users->random();

                $createdAt = now()->subDays(random_int(0, 90))
                    ->subHours(random_int(0, 23))
                    ->subMinutes(random_int(0, 59));

                $needsAssignee = in_array($statusKey, ['assigned', 'in_progress', 'completed'], true);

                $assignee = null;
                $assignedAt = null;
                $assignedBy = null;

                if ($needsAssignee) {
                    $assignee = $users->random();
                    $assignedAt = (clone $createdAt)->addHours(random_int(1, 24));
                    $assignedBy = $users->random();
                }

                $startedAt   = null;
                $completedAt = null;
                $cancelledAt = null;

                if (in_array($statusKey, ['in_progress', 'completed'], true)) {
                    $startedAt = (clone $assignedAt)->addHours(random_int(1, 48));
                }
                if ($statusKey === 'completed') {
                    $completedAt = (clone $startedAt)->addDays(random_int(1, 10));
                }
                if ($statusKey === 'cancelled') {
                    $cancelledAt = (clone $createdAt)->addDays(random_int(1, 5));
                }

                $dueDate = (clone $createdAt)->addDays(random_int(1, 30));

                $wo = WorkOrder::query()->create([
                    'code'            => sprintf('WO-%s-%04d', verta($createdAt)->format('Y'), $i),
                    'title'           => $titles[array_rand($titles)],
                    'description'     => $descriptions[array_rand($descriptions)],
                    'department_id'   => $department->id,
                    'assignee_id'     => $assignee?->id,
                    'assigned_by'     => $assignedBy?->id,
                    'assigned_at'     => $assignedAt,
                    'status_id'       => $status->id,
                    'priority_id'     => $priority->id,
                    'created_by'      => $creator->id,
                    'due_date'        => $dueDate->toDateString(),
                    'started_at'      => $startedAt,
                    'completed_at'    => $completedAt,
                    'cancelled_at'    => $cancelledAt,
                    'estimated_hours' => random_int(1, 40) + (random_int(0, 3) * 0.25),
                    'actual_hours'    => $completedAt ? random_int(1, 50) + (random_int(0, 3) * 0.25) : null,
                    'metadata'        => null,
                    'created_at'      => $createdAt,
                    'updated_at'      => $completedAt ?? $startedAt ?? $assignedAt ?? $createdAt,
                ]);


                $wo->statusHistories()->create([
                    'from_status_id' => null,
                    'to_status_id'   => $statuses['draft']->id,
                    'changed_by'     => $creator->id,
                    'note'           => 'ایجاد سفارش کار',
                    'created_at'     => $createdAt,
                ]);

                if ($statusKey !== 'draft') {
                    $pendingAt = (clone $createdAt)->addHours(random_int(1, 6));
                    $wo->statusHistories()->create([
                        'from_status_id' => $statuses['draft']->id,
                        'to_status_id'   => $statuses['pending']->id,
                        'changed_by'     => $creator->id,
                        'note'           => 'ارسال برای تخصیص',
                        'created_at'     => $pendingAt,
                    ]);
                }

                if (in_array($statusKey, ['assigned', 'in_progress', 'completed'], true)) {
                    $wo->statusHistories()->create([
                        'from_status_id' => $statuses['pending']->id,
                        'to_status_id'   => $statuses['assigned']->id,
                        'changed_by'     => $assignedBy->id,
                        'note'           => 'تخصیص به ' . $assignee->name,
                        'created_at'     => $assignedAt,
                    ]);
                }

                // ۴. assigned → in_progress
                if (in_array($statusKey, ['in_progress', 'completed'], true)) {
                    $wo->statusHistories()->create([
                        'from_status_id' => $statuses['assigned']->id,
                        'to_status_id'   => $statuses['in_progress']->id,
                        'changed_by'     => $assignee->id,
                        'note'           => 'شروع کار',
                        'created_at'     => $startedAt,
                    ]);
                }

                if ($statusKey === 'completed') {
                    $wo->statusHistories()->create([
                        'from_status_id' => $statuses['in_progress']->id,
                        'to_status_id'   => $statuses['completed']->id,
                        'changed_by'     => $assignee->id,
                        'note'           => 'اتمام کار با موفقیت',
                        'created_at'     => $completedAt,
                    ]);
                }

                if ($statusKey === 'cancelled') {
                    $fromKey = in_array($statusKey, ['pending', 'assigned']) ? $statusKey : 'pending';
                    $wo->statusHistories()->create([
                        'from_status_id' => $statuses[$fromKey]->id,
                        'to_status_id'   => $statuses['cancelled']->id,
                        'changed_by'     => $users->random()->id,
                        'note'           => 'لغو به دلیل تغییر اولویت‌ها',
                        'created_at'     => $cancelledAt,
                    ]);
                }


                if ($needsAssignee) {
                    $wo->assignments()->create([
                        'assigned_to'        => $assignee->id,
                        'assigned_by'        => $assignedBy->id,
                        'from_department_id' => null,
                        'to_department_id'   => $department->id,
                        'note'               => 'تخصیص اولیه به ' . $assignee->name,
                        'assigned_at'        => $assignedAt,
                        'unassigned_at'      => null,
                    ]);
                }


                if (in_array($statusKey, ['assigned', 'in_progress', 'completed'], true)) {
                    $template = $checklistTemplates[array_rand($checklistTemplates)];
                    $totalItems = count($template);

                    $doneCount = match ($statusKey) {
                        'completed'   => $totalItems,
                        'in_progress' => (int) ceil($totalItems / 2),
                        default       => 0,
                    };

                    foreach ($template as $index => $itemTitle) {
                        $isDone = $index < $doneCount;

                        WorkOrderChecklistItem::query()->create([
                            'work_order_id' => $wo->id,
                            'title'         => $itemTitle,
                            'is_done'       => $isDone,
                            'done_by'       => $isDone ? $assignee->id : null,
                            'done_at'       => $isDone ? (clone $startedAt)->addHours($index * 2) : null,
                            'sort_order'    => $index + 1,
                            'created_at'    => $assignedAt,
                            'updated_at'    => $isDone ? (clone $startedAt)->addHours($index * 2) : $assignedAt,
                        ]);
                    }
                }

                if ($i % 10 === 0) {
                    $this->command->info("   → {$i} سفارش ساخته شد...");
                }
            }
        });

        $count = WorkOrder::query()->count();
        $this->command->info("✅ مجموع سفارش‌های کار: {$count}");
    }
}
