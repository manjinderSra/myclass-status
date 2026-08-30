<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'requester_role',
        'requester_name',
        'ticket_id',
        'subject',
        'message',
        'priority',
        'status',
        'closed_at',
        'closed_by',
        'remarks'
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    /**
     * Get the school that owns the ticket.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the student that created the ticket.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the user that closed the ticket.
     */
    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /**
     * Get all messages for this ticket.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id');
    }

    /**
     * Get formatted ticket ID.
     */
    public function getFormattedTicketIdAttribute(): string
    {
        return $this->ticket_id ?? 'TCK-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Scope a query to only include open tickets.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope a query to only include tickets for a specific school.
     */
    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Close this ticket.
     */
    public function close($userId, $remarks = null): bool
    {
        return $this->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closed_by' => $userId,
            'remarks' => $remarks
        ]);
    }
}
