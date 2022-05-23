<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCenter extends Model
{
    use HasFactory;

    protected $table = 'training_centers';
    protected $primaryKey = 'training_center_id';

    protected $fillable = ['training_center', 'address', 'img_path'];

}
