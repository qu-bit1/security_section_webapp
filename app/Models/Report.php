<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Report extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'serial_number',
        'shift',
        'description',
        'user_id',
        'category_id',
        'status',
        'approved',
        'venue',
        'reporter',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'report_tag', 'report_id', 'tag_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attachments(): MorphToMany
    {
        return $this->morphToMany(Attachment::class, 'attachable');
    }

    public function remarks(): HasMany
    {
        return $this->hasMany(Remark::class);
    }

    public function uniqueIds(): array
    {
        return ['serial_number'];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($report) {
            $report->attachments()->detach();
            $report->tags()->detach();
        });
    }
}
