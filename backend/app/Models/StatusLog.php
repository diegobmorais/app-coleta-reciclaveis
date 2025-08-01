<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'status',
        'observation',
        'changed_at',
        'user_id',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
