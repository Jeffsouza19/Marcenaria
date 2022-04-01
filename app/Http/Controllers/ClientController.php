<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function add(Request $request){
        $array = ['error'=>''];

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'phone'=>'required|digits:11',
            'cpf'=>'required|digits:11|unique:clients,cpf',
            'email'=>'required|email|unique:clients,email',
            'address'=>'required',
            'password'=>'required',
            'password_confirm'=>'required|same:password'
        ]);

        if(!$validator->fails()){
            $name = $request->input('name');
            $phone = $request->input('phone');
            $cpf = $request->input('cpf');
            $email = $request->input('email');
            $address = $request->input('address');
            $password = $request->input('password');
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $client = new Client();
            $client->name = $name;
            $client->phone = $phone;
            $client->cpf = $cpf;
            $client->email = $email;
            $client->address = $address;
            $client->password = $hash;
            $client->save();

            $array['client'] = $client;
        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function getAll(){
        $array = ['error'=>''];

        $clients = Client::select('name', 'phone', 'address' ,'avatar')->get();

        $array['clients'] = $clients;

        return $array;
    }

    public function getOne($id){
        $array = ['error'=>''];

        $client = Client::find($id);

        if($client){
            $client = Client::select('name', 'phone', 'cpf', 'email', 'address', 'avatar')->where('id', $id)->get();
            $projects = Project::select('environment', 'description', 'price', 'photos')->where('id_client', $id)->get();

            $array['client'] = $client;
            $array['client']['projects'] = $projects;
        }else{
            $array['error'] = 'Cliente inexistente';
        }

        return $array;
    }

    public function update($id, Request $request){
        $array = ['error'=>''];

        $client = Client::find($id);
        if($client){
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'phone'=> 'required|digits:11',
                'address'=>'required'
            ]);
            if(!$validator->fails()){
                $name = $request->input('name');
                $phone = $request->input('phone');
                $address = $request->input('address');

                $client->name = $name;
                $client->phone = $phone;
                $client->address = $address;
                $client->save();


                $array['client'] = $client;
                $array['success'] = 'Cliente alterado com sucesso.';
            }else{
                $array['error'] = $validator->errors()->first();
                return $array;
            }
        }else{
            $array['error'] = 'Cliente inexistente.';
            return $array;
        }

        return $array;
    }

    public function delete($id){
        $array = ['error'=>''];

        $client = Client::find($id);
        if($client){
            $client->delete();
            $array['success'] = 'Cliente deletado com sucesso.';
        }else{
            $array['error'] = 'Cliente inexistente';
            return $array;
        }

        return $array;
    }
}
