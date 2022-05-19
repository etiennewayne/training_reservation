<?php

namespace App\Http\Controllers;

use App\Models\Appointment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetAppointmentController extends Controller
{
    //

    public function store(Request $req){

        $date =  $req->app_date;
        $ndate = date("Y-m-d", strtotime($date)); //convert to date format UNIX

        $time = $req->app_time;
        $ntime = date('H:i:s',strtotime($time)); //convert to format time UNIX

        $user = Auth::user();

        $n = time() . $user->lname . $user->fname;
        $refcode = substr(md5($n), -8);



        Appointment::create([
            'ref_no' => $refcode,
            'appointment_user_id' => $user->user_id,
            'app_date' => $ndate,
            'app_time' => $ntime,
            'app_status' => 0,
            'remarks' => $req->remarks,
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

}
