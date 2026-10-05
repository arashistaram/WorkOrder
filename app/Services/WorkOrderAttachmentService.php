<?php

namespace App\Services;

use App\Models\WorkOrder;
use App\Models\WorkOrderAttachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

final class WorkOrderAttachmentService
{
    public const int MAX_FILE_SIZE_KB = 30720;  // 30 MB

    public const array ALLOWED_MIMES = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain', 'text/csv',
        'application/zip',
        'application/x-rar-compressed',
        'video/mp4', 'video/mpeg',
        'audio/mpeg', 'audio/wav', 'audio/ogg',
    ];

    public function store(
        WorkOrder $workOrder,
        UploadedFile $file,
        ?int $statusHistoryId = null,
        ?string $note = null
    ): WorkOrderAttachment {
        $originalName = $file->getClientOriginalName();
        $mime         = $file->getMimeType() ?: 'application/octet-stream';
        $size         = $file->getSize();

        $directory = "work-orders/{$workOrder->id}/attachments";

        $extension = $file->getClientOriginalExtension();
        $baseName  = pathinfo($originalName, PATHINFO_FILENAME);
        $safeName  = Str::slug($baseName) ?: 'file';
        $fileName  = $safeName . '-' . now()->format('YmdHis') . '-' . Str::random(6)
            . ($extension ? '.' . $extension : '');

        // ذخیره روی دیسک
        $path = $file->storeAs($directory, $fileName, 'local');

        return WorkOrderAttachment::query()->create([
            'work_order_id'    => $workOrder->id,
            'status_history_id'=> $statusHistoryId,
            'uploaded_by'      => auth()->id(),
            'original_name'    => $originalName,
            'file_name'        => $fileName,
            'mime_type'        => $mime,
            'size'             => $size,
            'disk'             => 'local',
            'path'             => $path,
            'note'             => $note,
        ]);
    }

    public function storeMany(
        WorkOrder $workOrder,
        array $files,
        ?int $statusHistoryId = null,
        ?string $note = null
    ): array {
        $created = [];

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) continue;

            $created[] = $this->store($workOrder, $file, $statusHistoryId, $note);
        }

        return $created;
    }
}
