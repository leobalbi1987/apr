<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $primaryKey = 'service_id';

    protected $fillable = [
        'service_offer',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'service_id', 'service_id');
    }

    // Accessor to map service_name to service_offer for backward compatibility
    public function getServiceNameAttribute()
    {
        return $this->service_offer;
    }

    // Accessor for description (if needed by views)
    public function getDescriptionAttribute()
    {
        return $this->service_offer;
    }
}
