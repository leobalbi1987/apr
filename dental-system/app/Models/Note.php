<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';
    protected $primaryKey = 'note_id';

    protected $fillable = [
        'message',
        'date_created'
    ];

    protected $casts = [
        'date_created' => 'date'
    ];
}
