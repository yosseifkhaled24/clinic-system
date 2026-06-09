<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialty',
        'email',
        'phone',
        'image',
        'bio'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function reviews()
    {
     return $this->hasMany(Review::class);
    } 
    public function schedules()
    {
     return $this->hasMany(DoctorSchedule::class);
    }
}