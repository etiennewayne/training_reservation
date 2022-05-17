<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TraningCenter;

class OpenTrainingCentersController extends Controller
{
    //

    public function getOpenTrainingCenters(){
        return TraningCenter::all();
    }
}
