<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Member extends Authenticatable
{
    use HasFactory;

    protected $table = 'members';
    protected $primaryKey = 'member_id';

    protected $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'address',
        'email',
        'contact_no',
        'age',
        'gender',
        'username',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'password' => 'hashed'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'member_id', 'member_id');
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
