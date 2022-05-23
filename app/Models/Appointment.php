<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    protected $primaryKey = 'appointment_id';

    protected $fillable = ['ref_no', 'appointment_user_id', 'training_center_id',
        'app_date', 'app_time',
        'app_status', 'remarks'];


    public function user(){
        return $this->hasOne(User::class, 'user_id', 'appointment_user_id');
    }

    public function training_center(){
        return $this->hasOne(TrainingCenter::class, 'training_center_id', 'training_center_id');
    }


}
