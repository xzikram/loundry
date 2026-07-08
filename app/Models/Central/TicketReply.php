<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReply extends Model
{
    protected $fillable = [
        'ticket_id',
        'central_user_id',
        'replier_type',
        'replier_name',
        'message',
        'attachments',
        'is_internal',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_internal' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }

    public function centralUser(): BelongsTo
    {
        return $this->belongsTo(CentralUser::class, 'central_user_id');
    }
}
