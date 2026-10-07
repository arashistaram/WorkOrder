<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Collection;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('A description of what this tool does.')]
class ListDepartmentsTool extends Tool
{
    public function handle(Request $request): Response
    {
        $args = $request->all();
        $search = $args['search'] ?? null;

        $query = Collection::query()->orderBy('name');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        $depts = $query->get(['id', 'name', 'code']);

        return Response::json([
            'count' => $depts->count(),
            'items' => $depts->map(fn($d) => [
                'id'   => $d->id,
                'name' => $d->name,
                'code' => $d->code,
            ])->all(),
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => [
                'type'        => 'string',
                'description' => 'جستجو در نام یا کد واحد (اختیاری)',
            ],
        ];
    }
}
