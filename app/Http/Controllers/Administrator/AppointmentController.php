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

    public function getAppointmentTypes(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = \DB::table('appointment_types as a')
            ->join('offices as b', 'a.office_id', 'b.office_id')
            ->where('a.appointment_type', 'like', $req->type . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function show($id){
        return Appointment::find($id);
    }


    public function getAppointments(Request $req){
        $data = Appointment::with(['user', 'training_center'])->paginate($req->per_page);
        return $data;
    }



    public function update(Request $req, $id){
        $req->validate([
            'app_date' => ['required'],
            'app_time' => ['required'],
        ]);

        $date =  $req->app_date;
        $ndate = date("Y-m-d", strtotime($date)); //convert to date format UNIX

        $time = $req->app_time;
        $ntime = date('H:i:s',strtotime($time)); //convert to format time UNIX

        $data = Appointment::find($id);
        $data->training_center_id  = $req->training_center;
        $data->app_date  = $ndate;
        $data->app_time = $ntime;
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
