<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'cpf', 'cell'];

    public function consultations() {
        return $this->hasMany(Consultation::class);
    }

    public function doctors() {
        return $this->belongsToMany(Doctor::class, Consultation::class)->withPivot('date');
    }
}
