<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Action extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'report_id',
        'name',
        'description',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reports(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
