<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class WorkOrderAttachment extends Model
{

    public $table = 'work_order_attachments';

    protected $fillable = [
        'work_order_id', 'status_history_id', 'uploaded_by',
        'original_name', 'file_name', 'mime_type', 'size',
        'disk', 'path', 'note',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function statusHistory(): BelongsTo
    {
        return $this->belongsTo(WorkOrderStatusHistory::class, 'status_history_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getSizeHumanAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)       return number_format($bytes / 1024, 1) . ' KB';

        return $bytes . ' B';
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getIconAttribute(): string
    {
        $mime = $this->mime_type;

        return match (true) {
            str_starts_with($mime, 'image/')       => 'image',
            $mime === 'application/pdf'            => 'pdf',
            str_contains($mime, 'word')            => 'word',
            str_contains($mime, 'excel')
            || str_contains($mime, 'spreadsheet') => 'excel',
            str_contains($mime, 'zip')
            || str_contains($mime, 'compressed') => 'archive',
            str_starts_with($mime, 'video/')       => 'video',
            str_starts_with($mime, 'audio/')       => 'audio',
            str_starts_with($mime, 'text/')        => 'text',
            default                                 => 'file',
        };
    }

    public function deleteFile(): void
    {
        if (Storage::disk($this->disk)->exists($this->path)) {
            Storage::disk($this->disk)->delete($this->path);
        }
    }

    protected static function booted(): void
    {
        static::deleting(function (self $attachment) {
            $attachment->deleteFile();
        });
    }
}
