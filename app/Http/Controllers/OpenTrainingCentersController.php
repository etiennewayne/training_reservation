<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TrainingCenter;

class OpenTrainingCentersController extends Controller
{
    //

    public function getOpenTrainingCenters(){
        return TrainingCenter::all();
    }
}
