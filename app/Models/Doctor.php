<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'specialty', 'city_id'];

    public function consultations() {
        return $this->hasMany(Consultation::class);
    }

    public function patients() {
        return $this->belongsToMany(Patient::class, Consultation::class)->withPivot('date');
    }
}
