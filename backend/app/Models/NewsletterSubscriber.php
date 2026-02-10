<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'status', 'source', 'subscribed_at', 'unsubscribed_at', 'ip_address'
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'email' => 'encrypted', // GDPR Article 32 - Encrypt PII
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
