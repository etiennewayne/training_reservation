<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\TrainingCenter;
use Illuminate\Http\Request;

class TrainingCenterController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('administrator.training-centers');
    }

    public function show($id){
        return TrainingCenter::find($id);
    }

    public function getTrainingCenters(Request $req){
        $sort = explode('.', $req->sort_by);

        return TrainingCenter::where('training_center', 'like', $req->trainingcenter . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }

    public function store(Request $req){
        $req->validate([
            'training_center' => ['required', 'unique:training_centers']
        ]);
        TrainingCenter::create([
            'training_center' => strtoupper($req->training_center),
            'address' => strtoupper($req->address),
        ]);

        return response()->json([
            'status' => 'saved'
        ]);
    }

    public function update(Request $req, $id){
        $req->validate([
            'training_center' => ['required', 'unique:training_centers,training_center,' . $id .',training_center_id']
        ]);
        $data = TrainingCenter::find($id);
        $data->training_center = strtoupper($req->training_center);
        $data->address = strtoupper($req->address);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ]);
    }


    public function destroy($id){
        TrainingCenter::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ]);
    }

}
