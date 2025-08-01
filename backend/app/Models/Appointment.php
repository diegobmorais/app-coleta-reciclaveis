<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'protocol',
        'full_name',
        'street',
        'number',
        'neighborhood',
        'city',
        'preferred_date',
        'phone',
        'email',
        'status',
        'observation',
        'status_updated_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'status_updated_at' => 'datetime',
    ];

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'appointment_material');
    }

    public function statusLogs()
    {
        return $this->hasMany(StatusLog::class);
    }
}
