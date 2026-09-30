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
        $primaryUsers = [
            [
                'name'          => 'اقای بهرنگ',
                'username'      => 'behrang',
                'password'      => Hash::make('123456789'),
                'role'          => 'admin',
                'phone'         => '09120000001',
                'employee_code' => 'EMP-1001',
                'job_title'     => 'مدیر عامل',
                'is_active'     => true,
                'settings'      => ['theme' => 'dark'],
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
                'settings'      => ['theme' => 'light'],
            ],
            [
                'name'          => 'آرش نریمانی',
                'username'      => 'arash',
                'password'      => Hash::make('123456789'),
                'role'          => 'user',
                'phone'         => '09120000003',
                'employee_code' => 'EMP-1003',
                'job_title'     => 'برنامه نویس',
                'is_active'     => true,
                'settings'      => ['theme' => 'dark'],
            ],
            [
                'name'          => 'آرمین آقازاده',
                'username'      => 'armin',
                'password'      => Hash::make('123456789'),
                'role'          => 'user',
                'phone'         => '09120000004',
                'employee_code' => 'EMP-1004',
                'job_title'     => 'شبکه کار',
                'is_active'     => true,
                'settings'      => ['theme' => 'light'],
            ],
            [
                'name'          => 'امین منصور فر',
                'username'      => 'amin',
                'password'      => Hash::make('123456789'),
                'role'          => 'user',
                'phone'         => '09120000005',
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
                'phone'         => '09120000006',
                'employee_code' => 'EMP-1006',
                'job_title'     => 'سخت افزار',
                'is_active'     => false,
                'settings'      => null,
            ],
        ];

        foreach ($primaryUsers as $data) {
            User::query()->updateOrCreate(
                ['username' => $data['username']],
                $data
            );
        }


        $targetTotal = 50;
        $currentTotal = User::query()->count();
        $needed = max(0, $targetTotal - $currentTotal);

        if ($needed === 0) {
            $this->command->info("✅ کاربران از قبل کامل هستند ({$currentTotal} نفر).");
            return;
        }

        $firstNames = [
            'علی', 'محمد', 'رضا', 'حسین', 'سعید', 'مهدی', 'امیر', 'یاسر',
            'نیما', 'پارسا', 'کاوه', 'بابک', 'آرش', 'فرزاد', 'کامران', 'بهرام',
            'زهرا', 'فاطمه', 'مریم', 'سارا', 'نگار', 'الهام', 'شیما', 'نرگس',
            'پریسا', 'یاسمن', 'سمیرا', 'لیلا', 'هدیه', 'مینا', 'راضیه', 'شقایق',
            'میلاد', 'بهنام', 'سینا', 'کیوان', 'شهاب', 'فرشید', 'آرمان', 'پیمان',
            'رعنا', 'نازنین', 'شبنم', 'آیدا',
        ];

        $lastNames = [
            'رضایی', 'محمدی', 'حسینی', 'کریمی', 'موسوی', 'جعفری', 'احمدی',
            'صادقی', 'نوری', 'قاسمی', 'مرادی', 'یزدانی', 'شریفی', 'توکلی',
            'باقری', 'سلطانی', 'فرهادی', 'اکبری', 'رستمی', 'زمانی', 'کاظمی',
            'عباسی', 'هاشمی', 'قربانی', 'نادری', 'پارسا', 'شمس', 'خسروی',
        ];

        $jobTitles = [
            'برنامه نویس', 'دیتا آنالیست', 'شبکه کار', 'سخت افزار',
            'کارشناس فروش', 'کارشناس پشتیبانی', 'مدیر پروژه', 'طراح UI/UX',
            'کارشناس بازاریابی', 'حسابدار', 'منابع انسانی', 'دواپس',
            'کارشناس تست', 'مدیر محصول', 'کارشناس امنیت', 'کارشناس پایگاه داده',
        ];

        $roles  = ['user', 'user', 'user', 'user', 'manager'];
        $themes = ['dark', 'light'];

        $existingUsernames   = User::query()->pluck('username')->all();
        $existingEmployee    = User::query()->pluck('employee_code')->filter()->all();
        $existingPhones      = User::query()->pluck('phone')->filter()->all();

        $nextEmployee = $this->nextEmployeeNumber($existingEmployee);
        $nextPhone    = $this->nextPhoneNumber($existingPhones);

        $created  = 0;
        $attempts = 0;
        $maxAttempts = 2000;

        while ($created < $needed && $attempts < $maxAttempts) {
            $attempts++;

            $firstName = $firstNames[array_rand($firstNames)];
            $lastName  = $lastNames[array_rand($lastNames)];

            $baseUsername = $this->makeUsername($firstName, $lastName);
            $username     = $baseUsername;

            $suffix = 1;
            while (in_array($username, $existingUsernames, true)) {
                $username = $baseUsername . $suffix;
                $suffix++;
                if ($suffix > 200) {
                    continue 2;
                }
            }

            $employeeCode = 'EMP-' . $nextEmployee;
            $phone        = '0912' . str_pad((string) $nextPhone, 7, '0', STR_PAD_LEFT);

            if (in_array($employeeCode, $existingEmployee, true)) {
                $nextEmployee++;
                continue;
            }
            if (in_array($phone, $existingPhones, true)) {
                $nextPhone++;
                continue;
            }

            try {
                User::query()->create([
                    'name'          => $firstName . ' ' . $lastName,
                    'username'      => $username,
                    'password'      => Hash::make('123456789'),
                    'role'          => $roles[array_rand($roles)],
                    'phone'         => $phone,
                    'employee_code' => $employeeCode,
                    'job_title'     => $jobTitles[array_rand($jobTitles)],
                    'is_active'     => random_int(0, 100) > 10,
                    'settings'      => random_int(0, 1)
                        ? ['theme' => $themes[array_rand($themes)]]
                        : null,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                continue;
            }

            $existingUsernames[]  = $username;
            $existingEmployee[]   = $employeeCode;
            $existingPhones[]     = $phone;
            $nextEmployee++;
            $nextPhone++;
            $created++;
        }

        $total = User::query()->count();

        $this->command->info("✅ کاربران پیش‌فرض ساخته شدند.");
        $this->command->info("🆕 ایجاد شده در این اجرا: {$created} نفر");
        $this->command->info("📊 مجموع کاربران: {$total} نفر");
    }

    private function nextEmployeeNumber(array $existing): int
    {
        $max = 1006; // پیش‌فرض (چون اصلی‌ها تا EMP-1006 هستن)

        foreach ($existing as $code) {
            if (preg_match('/EMP-(\d+)/', (string) $code, $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        return $max + 1;
    }
    private function nextPhoneNumber(array $existing): int
    {
        $max = 6;

        foreach ($existing as $phone) {
            if (preg_match('/^0912(\d+)$/', (string) $phone, $m)) {
                $max = max($max, (int) ltrim($m[1], '0'));
            }
        }

        return $max + 1;
    }

    private function makeUsername(string $firstName, string $lastName): string
    {
        $map = [
            'علی' => 'ali', 'محمد' => 'mohammad', 'رضا' => 'reza', 'حسین' => 'hossein',
            'سعید' => 'saeed', 'مهدی' => 'mahdi', 'امیر' => 'amir', 'یاسر' => 'yaser',
            'نیما' => 'nima', 'پارسا' => 'parsa', 'کاوه' => 'kaveh', 'بابک' => 'babak',
            'آرش' => 'arash', 'فرزاد' => 'farzad', 'کامران' => 'kamran', 'بهرام' => 'bahram',
            'زهرا' => 'zahra', 'فاطمه' => 'fatemeh', 'مریم' => 'maryam', 'سارا' => 'sara',
            'نگار' => 'negar', 'الهام' => 'elham', 'شیما' => 'shima', 'نرگس' => 'narges',
            'پریسا' => 'parisa', 'یاسمن' => 'yasaman', 'سمیرا' => 'samira', 'لیلا' => 'leila',
            'هدیه' => 'hadiyeh', 'مینا' => 'mina', 'راضیه' => 'raziyeh', 'شقایق' => 'shaqayeq',
            'میلاد' => 'milad', 'بهنام' => 'behnam', 'سینا' => 'sina', 'کیوان' => 'keyvan',
            'شهاب' => 'shahab', 'فرشید' => 'farshid', 'آرمان' => 'arman', 'پیمان' => 'peyman',
            'رعنا' => 'rana', 'نازنین' => 'nazanin', 'شبنم' => 'shabnam', 'آیدا' => 'aida',

            'رضایی' => 'rezaei', 'محمدی' => 'mohammadi', 'حسینی' => 'hosseini',
            'کریمی' => 'karimi', 'موسوی' => 'mousavi', 'جعفری' => 'jafari',
            'احمدی' => 'ahmadi', 'صادقی' => 'sadeghi', 'نوری' => 'nouri',
            'قاسمی' => 'ghasemi', 'مرادی' => 'moradi', 'یزدانی' => 'yazdani',
            'شریفی' => 'sharifi', 'توکلی' => 'tavakoli', 'باقری' => 'bagheri',
            'سلطانی' => 'soltani', 'فرهادی' => 'farhadi', 'اکبری' => 'akbari',
            'رستمی' => 'rostami', 'زمانی' => 'zamani', 'کاظمی' => 'kazemi',
            'عباسی' => 'abbasi', 'هاشمی' => 'hashemi', 'قربانی' => 'ghorbani',
            'نادری' => 'naderi', 'پارسا' => 'parsa', 'شمس' => 'shams',
            'خسروی' => 'khosravi',
        ];

        $f = $map[$firstName] ?? Str::slug($firstName) ?: 'user';
        $l = $map[$lastName]  ?? Str::slug($lastName)  ?: '';

        return $l !== '' ? "{$f}.{$l}" : $f;
    }
}
