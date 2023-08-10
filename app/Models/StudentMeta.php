<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'dob',
        'address',
        'course_id',
        'client_id',
        'remember_token',
        'profile_photo',
        'verified',
        'verified_at',
        'verification_token',

    ];

    protected $casts = [
        'course_id' => 'array',
        'verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}