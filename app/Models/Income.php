<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['user_id', 'source', 'date', 'value'];

    protected $casts = [
        'date' => 'date', // or 'datetime' if you also need the time part
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
