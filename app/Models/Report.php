<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

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
        'approved_by',
        'dealing_officer',
        'approved_at',
        'reported_at',
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

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($report) {
            $report->attachments()->detach();
            $report->tags()->detach();
        });
    }
}
