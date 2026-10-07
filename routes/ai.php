<?php

use Laravel\Mcp\Facades\Mcp;

// Mcp::web('/mcp/demo', \App\Mcp\Servers\PublicServer::class);

return [
    \App\Mcp\Servers\WorkOrderServer::class => [
        \App\Mcp\Tools\GetWorkOrderStatsTool::class,
        \App\Mcp\Tools\GetWorkOrdersTool::class,
        \App\Mcp\Tools\GetWorkOrderByIdTool::class,
        \App\Mcp\Tools\GetOverdueWorkOrdersTool::class,
        \App\Mcp\Tools\GetWorkloadTool::class,
        \App\Mcp\Tools\CreateWorkOrderTool::class,
        \App\Mcp\Tools\CountWorkOrdersTool::class,
        \App\Mcp\Tools\DescribeWorkOrdersTool::class,
        \App\Mcp\Tools\GetCurrentUserTool::class,
        \App\Mcp\Tools\ListDepartmentsTool::class,
        \App\Mcp\Tools\RunSelectQueryTool::class,
    ],
];
