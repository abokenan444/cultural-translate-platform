<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'ticket_number',
        'name',
        'email',
        'phone',
        'category',
        'priority',
        'status',
        'subject',
        'message',
        'admin_response',
        'assigned_to',
        'responded_at',
        'resolved_at',
        'closed_at',
        'attachments',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'attachments' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($complaint) {
            if (!$complaint->ticket_number) {
                $complaint->ticket_number = 'TKT-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getCategoryTextAttribute()
    {
        return match($this->category) {
            'technical' => 'مشكلة تقنية',
            'billing' => 'الفواتير والاشتراكات',
            'feature_request' => 'طلب ميزة جديدة',
            'bug_report' => 'الإبلاغ عن خطأ',
            'other' => 'أخرى',
            default => $this->category,
        };
    }

    public function getPriorityTextAttribute()
    {
        return match($this->priority) {
            'low' => 'منخفضة',
            'medium' => 'متوسطة',
            'high' => 'عالية',
            'urgent' => 'عاجلة',
            default => $this->priority,
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'open' => 'مفتوحة',
            'in_progress' => 'قيد المعالجة',
            'waiting_response' => 'بانتظار الرد',
            'resolved' => 'تم الحل',
            'closed' => 'مغلقة',
            default => $this->status,
        };
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }
}
