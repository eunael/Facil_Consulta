<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Comment\Doc;

class City extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'state'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
