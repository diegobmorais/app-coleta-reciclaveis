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
        'suggested_date',
        'phone',
        'email',
        'observation',
        'status',        
        'status_observation',
        'status_updated_at',
    ];

    protected $casts = [
        'suggested_date' => 'date',
        'status_updated_at' => 'datetime',
    ];
    public const STATUS_PENDENTE = 'Pendente';
    public const STATUS_EM_ANDAMENTO = 'Em Andamento';
    public const STATUS_CONCLUIDO = 'Concluido';
    public const STATUS_CANCELADO = 'Cancelado';
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'appointment_material');
    }

    public function statusLogs()
    {
        return $this->hasMany(StatusLog::class);
    }
}
