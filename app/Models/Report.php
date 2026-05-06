<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'image',
        'latitude',
        'longitude',
        'status',
    ];

    // Single source of truth for categories — used in Blade and validation
    const CATEGORIES = [
        'Public Safety',
        'Noise Complaint',
        'Road Issue',
        'Environmental',
        'Other',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
