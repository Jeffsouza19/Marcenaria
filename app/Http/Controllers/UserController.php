<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getAll(){
        $array = ['error'=>''];

        $users = User::select('name', 'phone', 'avatar')->get();

        $array['users'] = $users;
        return $array;
    }

    public function getOne($id){
        $array = ['error'=>''];
        $user = User::where('id', $id)->exists();
        if($user){
            $user = User::select('name', 'phone', 'cpf', 'email', 'address', 'avatar')->where('id', $id)->get();

            $array['user'] = $user;

        }else{
            $array['error'] = 'Usuário inexistente';
            return $array;
        }

        return $array;
    }

    public function update($id, Request $request){
        $array = ['error'=>''];

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'phone'=> 'required|digits:11',
            'address'=>'required'
        ]);
        if(!$validator->fails()){
            $name = $request->input('name');
            $phone = $request->input('phone');
            $address = $request->input('address');

            $user = User::find($id);
            if($user){
                $user->name = $name;
                $user->phone = $phone;
                $user->address= $address;
                $user->save();

                $array['user'] = $user;
                $array['success'] = 'Usuário alterado com sucesso';
            }else{
                $array['error'] = 'Usuário inexistente';
            }
        }else{
            $array['error'] = $validator->errors()->first();
        }

        return $array;
    }

    public function delete($id){
        $array = ['error'=>''];

        $user = User::find($id);
        if($user){
            $user->delete();

            $array['success'] = 'Usuario deletado com sucesso.';
        }else{
            $array['error'] = 'Usuário inexistente.';
            return $array;
        }

        return $array;
    }
}
