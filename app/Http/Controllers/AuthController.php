<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function unauthorized(){
        return response()->json([
            'error'=>'Não Autorizado'
        ], 401);
    }

    public function register(Request $request){
        $array = ['error'=>''];

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required|unique:users,email',
            'cpf'=>'required|unique:users,cpf|digits:11',
            'password'=>'required',
            'password_confirm'=>'required|same:password'
        ]);

        if(!$validator->fails()){
            $name = $request->input('name');
            $email = $request->input('email');
            $cpf = $request->input('cpf');
            $password = $request->input('password');
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $newUser = new User();
            $newUser->name = $name;
            $newUser->email = $email;
            $newUser->cpf = $cpf;
            $newUser->password = $hash;
            $newUser->save();

            $token = auth()->attempt([
                'email'=>$email,
                'password'=>$password
            ]);

            if(!$token){
                $array['error'] = 'Ocorreu um erro!';
                return $array;
            }

            $array['token'] = $token;

            $user = auth()->user();
            $array['user'] = $user;

            $projects = Project::select(['id', 'environment'])
                        ->where('id_client', $user['id'])
                        ->get();

            $array['user']['projects'] = $projects;


        }else{
            $array['error'] = $validator->errors()->first();
        }
        return $array;
    }

    public function login(Request $request){
        $array = ['error'=>''];

        $validator = Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required'
        ]);

        if(!$validator->fails()){
            $email = $request->input('email');
            $password = $request->input('password');

            $token = auth()->attempt([
                'email'=>$email,
                'password'=>$password
            ]);

            if(!$token){
                $array['error'] = 'Email e/ou senha estão errados.';
                return $array;
            }

            $array['token'] = $token;

            $user = auth()->user();
            $array['user'] = $user;

            $projects = Project::select(['id', 'environment'])
                        ->where('id_client', $user['id'])
                        ->get();

            $array['user']['projects'] = $projects;

        }else{
            $array['error'] = $validator->errors()->first();
        }
        return $array;
    }

    public function validateToken(){
        $array = ['error'=>''];

        $user = auth()->user();
        $array['user'] = $user;

        $projects = Project::select(['id', 'environment'])
                    ->where('id_client', $user['id'])
                    ->get();

        $array['user']['projects'] = $projects;

        return $array;
    }

    public function logout(){
        $array= ['error'=>''];
        auth()->logout();
        return $array;
    }
}
