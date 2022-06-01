<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function add(Request $request){
        $array = ['error'=>''];

        $validator = Validator::make($request->all(),[
            'client' => 'required',
            'description' => 'required',
            'environment' => 'required',
            'price' => 'required'
        ]);

        if(!$validator->fails()){
            $client = $request->input('client');
            $fitter = $request->input('fitter');
            $woodworker = $request->input('woodworker');
            $description = $request->input('description');
            $environment = $request->input('environment');
            $price = $request->input('price');
            $photos = $request->input('photos');

            $idClient = Client::where('name', $client)->firstorfail();
            $idClient = $idClient->id;

            $idFitter = Employee::where('name', $fitter)->firstorfail();
            $idFitter = $idFitter->id;

            $idWoodworker = Employee::where('name', $woodworker)->firstorfail();
            $idWoodworker = $idWoodworker->id;

            $project = new Project();
            $project->id_client = $idClient;
            $project->id_fitter = $idFitter;
            $project->id_woodworker = $idWoodworker;
            $project->description = $description;
            $project->environment = $environment;
            $project->price = $price;
            $project->photos = $photos;
            $project->save();


            $array['project'] = $project;

        }else{
            $array['error'] = $validator->errors()->first();

            return $array;
        }

        return $array;
    }

    public function getAll() {
        $array = ['error'=>''];

        $projects = Project::all();
        $array['projects'] = $projects;

        return $array;
    }
}
