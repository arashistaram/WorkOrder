<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Mcp\Request;

final class McpAgentService
{
    protected string $ollamaUrl;
    protected string $model;
    protected int $timeout;
    protected int $maxIterations;

    protected array $toolClasses = [
        \App\Mcp\Tools\GetCurrentUserTool::class,
        \App\Mcp\Tools\CountWorkOrdersTool::class,
        \App\Mcp\Tools\GetWorkOrdersTool::class,
        \App\Mcp\Tools\GetOverdueWorkOrdersTool::class,
        \App\Mcp\Tools\ListDepartmentsTool::class,
        \App\Mcp\Tools\GroupWorkOrdersTool::class,
        \App\Mcp\Tools\DescribeDatabaseSchemaTool::class,
        \App\Mcp\Tools\RunSelectQueryTool::class,
    ];

    /** @var array<string, string> map snake_name → class */
    protected array $toolMap = [];
    protected array $toolSchemas = [
        'get_current_user' => [
            'type'       => 'object',
            'properties' => [],
        ],

        'count_work_orders' => [
            'type' => 'object',
            'properties' => [
                'department_name'   => ['type' => 'string',  'description' => 'نام واحد (مثال: انفورماتیک)'],
                'department_id'     => ['type' => 'integer', 'description' => 'شناسه واحد'],
                'status'            => ['type' => 'string',  'description' => 'draft, pending, assigned, in_progress, completed, cancelled'],
                'statuses'          => ['type' => 'array',   'items' => ['type' => 'string']],
                'priority'          => ['type' => 'string',  'description' => 'low, medium, high, critical'],
                'priorities'        => ['type' => 'array',   'items' => ['type' => 'string']],
                'assignee_username' => ['type' => 'string',  'description' => 'نام کاربری مسئول'],
                'is_open'           => ['type' => 'boolean'],
                'is_final'          => ['type' => 'boolean'],
                'overdue_only'      => ['type' => 'boolean'],
                'due_today'         => ['type' => 'boolean'],
                'due_from'          => ['type' => 'string',  'description' => 'تاریخ Y-m-d'],
                'due_to'            => ['type' => 'string',  'description' => 'تاریخ Y-m-d'],
                'created_from'      => ['type' => 'string',  'description' => 'تاریخ Y-m-d'],
                'created_to'        => ['type' => 'string',  'description' => 'تاریخ Y-m-d'],
                'this_month'        => ['type' => 'boolean', 'description' => 'این ماه شمسی'],
                'this_week'         => ['type' => 'boolean', 'description' => 'این هفته شمسی'],
                'today'             => ['type' => 'boolean'],
            ],
        ],

        'get_work_orders' => [
            'type' => 'object',
            'properties' => [
                'department_name'   => ['type' => 'string'],
                'department_id'     => ['type' => 'integer'],
                'status'            => ['type' => 'string'],
                'statuses'          => ['type' => 'array', 'items' => ['type' => 'string']],
                'priority'          => ['type' => 'string'],
                'priorities'        => ['type' => 'array', 'items' => ['type' => 'string']],
                'assignee_username' => ['type' => 'string'],
                'is_open'           => ['type' => 'boolean'],
                'is_final'          => ['type' => 'boolean'],
                'overdue_only'      => ['type' => 'boolean'],
                'due_today'         => ['type' => 'boolean'],
                'due_from'          => ['type' => 'string'],
                'due_to'            => ['type' => 'string'],
                'created_from'      => ['type' => 'string'],
                'created_to'        => ['type' => 'string'],
                'this_month'        => ['type' => 'boolean'],
                'this_week'         => ['type' => 'boolean'],
                'today'             => ['type' => 'boolean'],
                'sort_by'           => ['type' => 'string',  'description' => 'id, created_at, due_date, priority_id'],
                'sort_dir'          => ['type' => 'string',  'description' => 'asc یا desc'],
                'limit'             => ['type' => 'integer', 'description' => 'حداکثر 50'],
            ],
        ],

        'get_overdue_work_orders' => [
            'type'       => 'object',
            'properties' => [],
        ],

        'list_departments' => [
            'type' => 'object',
            'properties' => [
                'search' => ['type' => 'string', 'description' => 'جستجو در نام یا کد واحد'],
            ],
        ],

        'describe_database_schema' => [
            'type' => 'object',
            'properties' => [
                'table' => ['type' => 'string', 'description' => 'نام جدول (اختیاری)'],
            ],
        ],

        'run_select_query' => [
            'type' => 'object',
            'properties' => [
                'sql'      => ['type' => 'string', 'description' => 'کوئری SELECT معتبر'],
                'question' => ['type' => 'string', 'description' => 'سوال اصلی کاربر'],
            ],
            'required' => ['sql'],
        ],
    ];

    public function __construct()
    {
        $this->ollamaUrl     = config('services.ollama.url', 'http://localhost:11434');
        $this->model         = config('services.ollama.model', 'llama3.1:8b');
        $this->timeout       = (int) config('services.ollama.timeout', 300);
        $this->maxIterations = 5;

        $this->buildToolMap();
    }

    protected function buildToolMap(): void
    {
        foreach ($this->toolClasses as $class) {
            if (! class_exists($class)) {
                continue;
            }

            $short = class_basename($class);
            $short = preg_replace('/Tool$/', '', $short);
            $snake = Str::snake($short);

            $this->toolMap[$snake] = $class;
        }

        Log::info('MCP tool map built', $this->toolMap);
    }


    protected function toolsForOllama(): array
    {
        $out = [];

        foreach ($this->toolMap as $snakeName => $className) {
            try {
                $description = $this->extractDescription($className);

                $parameters = $this->toolSchemas[$snakeName] ?? [
                    'type'       => 'object',
                    'properties' => [],
                ];

                if (isset($parameters['properties'])
                    && is_array($parameters['properties'])
                    && empty($parameters['properties'])) {
                    $parameters['properties'] = new \stdClass();
                }

                $out[] = [
                    'type' => 'function',
                    'function' => [
                        'name'        => $snakeName,
                        'description' => $description ?: "Tool: {$snakeName}",
                        'parameters'  => $parameters,
                    ],
                ];
            } catch (\Throwable $e) {
                Log::error('MCP: failed to build tool', [
                    'class' => $className,
                    'err'   => $e->getMessage(),
                ]);
            }
        }

        return $out;
    }

    protected function extractDescription(string $className): string
    {
        try {
            $reflection = new \ReflectionClass($className);

            foreach ($reflection->getAttributes() as $attr) {
                $attrName = $attr->getName();

                if (str_contains($attrName, 'Description')) {
                    $inst = $attr->newInstance();

                    if (method_exists($inst, 'getDescription')) {
                        return $inst->getDescription();
                    }
                    if (property_exists($inst, 'description')) {
                        return (string) $inst->description;
                    }
                    if (property_exists($inst, 'value')) {
                        return (string) $inst->value;
                    }
                    if (method_exists($inst, '__toString')) {
                        return (string) $inst;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('MCP: desc extract failed', [
                'class' => $className,
                'err'   => $e->getMessage(),
            ]);
        }

        return '';
    }

    protected function callTool(string $name, array $arguments): array
    {
        try {
            $className = $this->toolMap[$name] ?? null;

            if (! $className) {
                return ['error' => "ابزار '{$name}' یافت نشد."];
            }

            $instance = new $className();
            $request  = new Request($arguments);
            $response = $instance->handle($request);

            $result = $this->responseToArray($response);

            Log::info('Tool result', [
                'tool'   => $name,
                'args'   => $arguments,
                'result' => $result,
            ]);

            return $result;

        } catch (\Throwable $e) {
            Log::error('MCP: tool call failed', [
                'tool'  => $name,
                'args'  => $arguments,
                'err'   => $e->getMessage(),
            ]);
            return ['error' => $e->getMessage()];
        }
    }

    protected function responseToArray($response): array
    {
        if (is_array($response)) {
            return $this->unwrapMcpResponse($response);
        }

        if (is_string($response)) {
            $decoded = json_decode($response, true);
            return is_array($decoded)
                ? $this->unwrapMcpResponse($decoded)
                : ['text' => $response];
        }

        if (! is_object($response)) {
            return ['result' => $response];
        }

        foreach (['toArray', 'json', 'getData', 'all', 'toJson'] as $method) {
            if (! method_exists($response, $method)) {
                continue;
            }

            try {
                $m = new \ReflectionMethod($response, $method);
                if (! $m->isPublic()) {
                    continue;
                }

                $value = $response->$method();

                if (is_array($value)) {
                    return $this->unwrapMcpResponse($value);
                }

                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (is_array($decoded)) {
                        return $this->unwrapMcpResponse($decoded);
                    }
                    return ['text' => $value];
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        // ۲. Reflection برای property های محافظت‌شده
        try {
            $reflection = new \ReflectionObject($response);
            $allProps   = [];

            foreach ($reflection->getProperties() as $prop) {
                $name = $prop->getName();
                try {
                    $prop->setAccessible(true);
                    $val = $prop->getValue($response);

                    if (is_scalar($val) || is_null($val) || is_array($val)) {
                        $allProps[$name] = $val;
                    } elseif (is_object($val) && method_exists($val, '__toString')) {
                        $allProps[$name] = (string) $val;
                    }
                } catch (\Throwable $e) {
                    continue;
                }
            }

            // دنبال content/text/data بگرد
            foreach (['content', 'text', 'data', 'value', 'json', 'body', 'payload'] as $key) {
                if (! isset($allProps[$key])) {
                    continue;
                }

                $val = $allProps[$key];

                if (is_string($val)) {
                    $decoded = json_decode($val, true);
                    if (is_array($decoded)) {
                        return $this->unwrapMcpResponse($decoded);
                    }
                    return ['text' => $val];
                }

                if (is_array($val)) {
                    return $this->unwrapMcpResponse($val);
                }
            }

            if (! empty($allProps)) {
                return $allProps;
            }
        } catch (\Throwable $e) {
            Log::warning('Response reflection failed', ['err' => $e->getMessage()]);
        }

        Log::error('Response parse completely failed', [
            'class'   => get_class($response),
            'methods' => get_class_methods($response),
        ]);

        return ['error' => 'Response could not be parsed'];
    }

    /**
     * استخراج محتوای واقعی از ساختار MCP Response
     * {"type":"text","text":"{\"id\":1,...}"} → {"id":1,...}
     */
    protected function unwrapMcpResponse(array $data): array
    {
        // {"type":"text","text":"..."}
        if (isset($data['type'], $data['text']) && is_string($data['text'])) {
            $inner = json_decode($data['text'], true);
            if (is_array($inner)) {
                return $inner;
            }
            return ['text' => $data['text']];
        }

        // آرایه‌ای از این آبجکت‌ها
        if (isset($data[0]) && is_array($data[0])) {
            $merged = [];
            foreach ($data as $item) {
                if (is_array($item) && isset($item['type'], $item['text'])) {
                    $inner = json_decode($item['text'], true);
                    if (is_array($inner)) {
                        $merged = array_merge($merged, $inner);
                    }
                } elseif (is_array($item)) {
                    $merged = array_merge($merged, $item);
                }
            }
            return $merged;
        }

        return $data;
    }

    /* =========================================================
     *  حلقه اصلی Agent
     * ========================================================= */

    public function chat(string $userMessage = '', array $history = []): array
    {
        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt()],
        ];

        foreach ($history as $h) {
            if (! isset($h['role'], $h['content'])) {
                continue;
            }

            $content = (string) ($h['content'] ?? '');

            // skip پیام‌های user خالی
            if ($h['role'] === 'user' && trim($content) === '') {
                continue;
            }

            $messages[] = [
                'role'    => $h['role'],
                'content' => $content,
            ];
        }

        if (trim($userMessage) !== '') {
            $messages[] = ['role' => 'user', 'content' => $userMessage];
        }

        $tools       = $this->toolsForOllama();
        $toolsUsed   = [];
        $iterations  = 0;
        $finalAnswer = '';

        Log::info('Agent start', [
            'tools' => array_map(fn($t) => $t['function']['name'], $tools),
            'query' => $userMessage,
        ]);

        while ($iterations < $this->maxIterations) {
            $iterations++;

            $response = $this->callOllama($messages, $tools);

            if (! $response) {
                return [
                    'content'    => 'خطا در ارتباط با مدل هوش مصنوعی. لطفاً دوباره امتحان کنید.',
                    'tools_used' => $toolsUsed,
                    'iterations' => $iterations,
                ];
            }

            $message   = $response['message'] ?? [];
            $toolCalls = $message['tool_calls'] ?? [];
            $content   = $message['content'] ?? '';

            Log::info('Agent iter', [
                'iter'       => $iterations,
                'tool_calls' => count($toolCalls),
                'content'    => mb_substr($content, 0, 200),
            ]);

            if (empty($toolCalls)) {
                $finalAnswer = $content;
                break;
            }

            $normalizedToolCalls = $this->normalizeToolCalls($toolCalls);

            $messages[] = [
                'role'       => 'assistant',
                'content'    => $content ?: '',
                'tool_calls' => $normalizedToolCalls,
            ];

            foreach ($toolCalls as $call) {
                $name = $call['function']['name'] ?? null;
                $args = $call['function']['arguments'] ?? [];

                if (is_string($args)) {
                    $decoded = json_decode($args, true);
                    $args = is_array($decoded) ? $decoded : [];
                }
                if (! is_array($args)) {
                    $args = [];
                }

                if (! $name) {
                    continue;
                }

                Log::info('Executing tool', ['name' => $name, 'args' => $args]);

                $result = $this->callTool($name, $args);

                $toolsUsed[] = [
                    'name'   => $name,
                    'args'   => $args,
                    'result' => $result,
                ];

                $messages[] = [
                    'role'      => 'tool',
                    'tool_name' => $name,
                    'content'   => json_encode($result, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                ];
            }
        }

        return [
            'content'    => $finalAnswer ?: 'نتوانستم پاسخ مناسبی بسازم.',
            'tools_used' => $toolsUsed,
            'iterations' => $iterations,
        ];
    }

    /* =========================================================
     *  ارسال به Ollama
     * ========================================================= */

    /**
     * Native Ollama /api/chat requires function.arguments to be an object.
     * Keep tool-call metadata and nested parameter arrays intact.
     */
    protected function normalizeToolCalls(array $toolCalls): array
    {
        $normalized = [];
        foreach ($toolCalls as $call) {
            if (! is_array($call)
                || ! is_array($call['function'] ?? null)
                || ! is_string($call['function']['name'] ?? null)
                || $call['function']['name'] === '') {
                throw new \UnexpectedValueException('Invalid tool-call function name.');
            }

            $arguments = $call['function']['arguments'] ?? [];
            if (is_string($arguments)) {
                // Decode strings from older adapters; never double-encode JSON.
                $arguments = json_decode($arguments, false, 512, JSON_THROW_ON_ERROR);
                if (! $arguments instanceof \stdClass) {
                    throw new \UnexpectedValueException('Encoded tool arguments must contain a JSON object.');
                }
            }
            if (is_array($arguments)) {
                // Laravel decodes native {} to []; cast only the top-level object.
                if ($arguments !== [] && array_is_list($arguments)) {
                    throw new \UnexpectedValueException('Tool arguments must be an object, not a list.');
                }
                $arguments = (object) $arguments;
            }
            if (! $arguments instanceof \stdClass) {
                throw new \UnexpectedValueException('Tool arguments must be a JSON object.');
            }

            $call['function']['arguments'] = $arguments;
            $normalized[] = $call;
        }
        return $normalized;
    }

    protected function callOllama(array $messages, array $tools): ?array
    {
        try {
            $cleanMessages = [];
            foreach ($messages as $msg) {
                $clean = ['role' => $msg['role']];
                $clean['content'] = $msg['content'] ?? '';

                if (! empty($msg['tool_calls'])) {
                    $clean['tool_calls'] = $this->normalizeToolCalls($msg['tool_calls']);
                }
                if (isset($msg['tool_name'])) {
                    $clean['tool_name'] = $msg['tool_name'];
                }

                $cleanMessages[] = $clean;
            }

            $payload = [
                'model'    => $this->model,
                'messages' => $cleanMessages,
                'stream'   => false,
                'think'    => false,
                'options'  => [
                    'temperature' => 0.1,
                    'num_ctx'     => 8192,
                    'num_predict' => 1024,
                    'top_p'       => 0.9,
                ],
            ];

            if (! empty($tools)) {
                $payload['tools'] = $tools;
            }

            Log::info('Ollama payload', [
                'model'    => $payload['model'],
                'tools_n'  => count($tools),
                'messages' => array_map(fn($m) => [
                    'role'        => $m['role'],
                    'content_len' => strlen($m['content'] ?? ''),
                    'has_tools'   => ! empty($m['tool_calls']),
                ], $cleanMessages),
            ]);

            $response = Http::timeout($this->timeout)
                ->post("{$this->ollamaUrl}/api/chat", $payload);

            if (! $response->successful()) {
                Log::warning('Ollama failed', [
                    'status' => $response->status(),
                    'body'   => mb_substr($response->body(), 0, 1000),
                ]);
                return null;
            }

            $json = $response->json();

            Log::info('Ollama response', [
                'tool_calls' => count($json['message']['tool_calls'] ?? []),
                'content'    => mb_substr($json['message']['content'] ?? '', 0, 200),
            ]);

            return $json;

        } catch (\Throwable $e) {
            Log::error('Ollama exception', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    /* =========================================================
     *  System Prompt
     * ========================================================= */

    protected function systemPrompt(): string
    {
        $user = auth()->user();
        $name = $user?->name ?? 'کاربر';
        $role = $user?->role ?? 'guest';

        return <<<PROMPT
            تو دستیار هوشمند سیستم سفارش کار هستی.

            ## کاربر جاری:
            - نام: {$name}
            - نقش: {$role}

            ## قوانین:
            1. همیشه فارسی جواب بده — کوتاه و دقیق.
            2. هرگز از خودت عدد یا داده نساز. فقط از خروجی ابزارها استفاده کن.
            3. اگر ابزار خطا داد، بگو دریافت داده با خطا مواجه شد. فقط اگر ابزار موفق بود و نتیجه خالی بود بگو «داده‌ای یافت نشد».
            4. برای «چند تا» از count_work_orders استفاده کن.
            5. برای «نشون بده/کدام» از get_work_orders استفاده کن.
            6. برای «به تفکیک X» از group_work_orders استفاده کن.
            7. برای «اسم من/نقش من» از get_current_user استفاده کن.
            8. اسم واحد رو با department_name پاس بده (نه ID).
            9. برای SQL خام از run_select_query استفاده کن (فقط ادمین).
            10. برای «به تفکیک X» یا «بر اساس X» از `group_work_orders` استفاده کن (نه count_work_orders).
             11. اگه مطمئن نیستی ستون‌ها چی هستن، `describe_database_schema` رو صدا بزن.

            ## نمونه‌های «به تفکیک»:
            - «به تفکیک وضعیت چند سفارش داریم؟» → `group_work_orders({group_by:"status"})`
            - «به تفکیک اولویت چطوره؟» → `group_work_orders({group_by:"priority"})`
            - «هر واحد چند سفارش داره؟» → `group_work_orders({group_by:"department"})`
            - «بار کاری هر مسئول؟» → `group_work_orders({group_by:"assignee"})`
            - «روند ماهانه چیه؟» → `group_work_orders({group_by:"month"})`

            ## ساختار پاسخ ابزارها:
            ابزارها معمولاً این شکلی جواب میدن:
            - `{"success":true, "count":5, "rows":[...]}` → ۵ ردیف داده داری، به کاربر نشون بده
            - `{"success":true, "answer":60, "hint":"پاسخ 60 است"}` → جواب مستقیم: 60
            - `{"success":true, "count":0, "rows":[]}` → واقعاً دیتایی نیست
            - `{"success":false, "error":"..."}` → خطا رخ داده

            ## ابزارها:
            - get_current_user — پروفایل کاربر
            - count_work_orders — شمارش با فیلتر
            - get_work_orders — لیست با فیلتر
            - get_overdue_work_orders — معوق‌ها
            - list_departments — لیست واحدها
            - group_work_orders — گروه‌بندی (به تفکیک)
            - run_select_query — SQL خام (فقط ادمین)

            ## اسکیمای دیتابیس (مهم — برای SQL خام از این ستون‌ها استفاده کن):

            ### `work_orders` (سفارش‌های کار):
            - id, code, title, description
            - department_id, assignee_id, assigned_by, created_by
            - status_id, priority_id
            - due_date (DATE), started_at (DATETIME), completed_at (DATETIME), cancelled_at (DATETIME), assigned_at (DATETIME)
            - estimated_hours (DECIMAL), actual_hours (DECIMAL)
            - created_at, updated_at, deleted_at

            **مدت زمان تکمیل = `DATEDIFF(completed_at, started_at)` روز**
            **مدت زمان تخمینی = `estimated_hours` ساعت**

            ### `work_order_statuses`:
            - id, key (draft/pending/assigned/in_progress/completed/cancelled), label, color, is_final, is_active

            ### `work_order_priorities`:
            - id, key (low/medium/high/critical), label, color, level

            ### `departments`:
            - id, name, code, phone, location, is_active, deleted_at

            ### `users`:
            - id, name, username, role (admin/manager/user), employee_code, job_title, phone, is_active, deleted_at

            ### `department_users` (pivot):
            - user_id, department_id, role, is_active

            ### `work_order_checklist_items`:
            - id, work_order_id, title, is_done, done_by, done_at, sort_order

            ### `work_order_status_histories`:
            - id, work_order_id, from_status_id, to_status_id, changed_by, note, created_at

            ### `work_order_assignments`:
            - id, work_order_id, assigned_to, assigned_by, from_department_id, to_department_id, assigned_at, unassigned_at

            ## نمونه‌های SQL درست:

            **میانگین زمان تکمیل به تفکیک اولویت:**
            ```sql
            SELECT p.label AS priority,
                   ROUND(AVG(DATEDIFF(w.completed_at, w.started_at)), 1) AS avg_days,
                   COUNT(w.id) AS completed_count
            FROM work_orders w
            JOIN work_order_priorities p ON p.id = w.priority_id
            WHERE w.deleted_at IS NULL
              AND w.completed_at IS NOT NULL
              AND w.started_at IS NOT NULL
            GROUP BY p.id, p.label, p.level
            ORDER BY p.level DESC
            PROMPT;
    }
}
