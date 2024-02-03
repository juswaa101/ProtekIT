<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPlanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'start',
        'end',
        'location',
        'description',
        'color',
    ];

    /**
     * Get the user that owns the EventPlanner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
