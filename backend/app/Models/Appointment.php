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
        'status_updated_at',
    ];

    protected $casts = [
        'suggested_date' => 'date',
        'status_updated_at' => 'datetime',
    ];
    protected $with = [
        'materials',
        'statusLogs'
    ];
    public const STATUS_PENDENTE  = 'Pendente';
    public const STATUS_AGENDADO  = 'Agendado';
    public const STATUS_CONCLUIDO = 'Concluído';
    public const STATUS_CANCELADO = 'Cancelado';
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'appointment_material');
    }

    public function statusLogs()
    {
        return $this->hasMany(StatusLog::class)->orderBy('created_at', 'desc');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
