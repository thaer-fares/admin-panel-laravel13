<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SentEmail extends Model
{
    protected $fillable = [
        'sender_id',
        'subject',
        'body',
        'audience',
        'audience_role',
        'recipients_count',
        'failed_count',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
