<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){
        return view('administrator.appointments');
    }

    // public function getAppointmentTypes(Request $req){
    //     $sort = explode('.', $req->sort_by);

    //     $data = \DB::table('appointment_types as a')
    //         ->join('offices as b', 'a.office_id', 'b.office_id')
    //         ->where('a.appointment_type', 'like', $req->type . '%')
    //         ->orderBy($sort[0], $sort[1])
    //         ->paginate($req->perpage);

    //     return $data;
    // }

    public function show($id){
        return Appointment::find($id);
    }


    public function getAppointments(Request $req){
        $data = Appointment::with(['user', 'training_center'])
            ->where('ref_no', 'like', $req->reference . '%')
            ->paginate($req->per_page);
        return $data;
    }



    public function update(Request $req, $id){
       
        $req->validate([
            'app_date' => ['required'],
            'app_time_from' => ['required'],
            'app_time_to' => ['required'],

        ]);

        $date =  $req->app_date;
        $ndate = date("Y-m-d", strtotime($date)); //convert to date format UNIX

        $timeFrom = $req->app_time_from;
        $timeTo = $req->app_time_to;

        $ntimeFrom = date('H:i:s',strtotime($timeFrom)); //convert to format time UNIX
        $ntimeTo = date('H:i:s',strtotime($timeTo)); //convert to format time UNIX

        $data = Appointment::find($id);
        $data->training_center_id  = $req->training_center;
        $data->app_date  = $ndate;
        $data->app_time_from = $ntimeFrom;
        $data->app_time_to = $ntimeTo;
        $data->remarks = $req->remarks;
        $data->save();

        return response()->json([
            'status' => 'updated'
        ],200);
    }



    public function approveAppointment($id){
        $data = Appointment::find($id);

        $data->app_status = 1;
        $data->save();

        return response()->json([
            'status' => 'approved'
        ],200);
    }

    public function destroy($id){
        AppointmentType::destroy($id);
    }

}
