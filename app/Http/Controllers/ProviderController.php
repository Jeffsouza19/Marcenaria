<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    public function add(Request $request){
        $array = ['error'=>''];

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'phone'=>'required|digits:11',
            'cnpj'=>'required|digits:14|unique:providers,cnpj',
            'email'=>'required|email|unique:providers,email',
            'address'=>'required'
        ]);

        if(!$validator->fails()){
            $name = $request->input('name');
            $phone = $request->input('phone');
            $cnpj = $request->input('cnpj');
            $email = $request->input('email');
            $address = $request->input('address');

            $newProvider = new Provider();
            $newProvider->name = $name;
            $newProvider->phone = $phone;
            $newProvider->cnpj = $cnpj;
            $newProvider->email = $email;
            $newProvider->address = $address;
            $newProvider->save();

            $array['provider'] = $newProvider;
        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array;
    }

    public function getAll(){
        $array = ['error'=>''];

        $providers = Provider::select('name', 'phone', 'avatar')->get();

        $array['providers'] = $providers;
        return $array;
    }

    public function getOne($id){
        $array = ['error'=>''];

        $provider = Provider::where('id', $id)->exists();

        if($provider){
            $provider = Provider::select('name', 'phone', 'cnpj', 'email', 'address', 'avatar')->where('id', $id)->get();
            $products = Inventory::select('product', 'quantaty', 'description')->where('id_provider', $id)->get();
            $array['provider'] = $provider;
            $array['provider']['products'] = $products;
        }else{
            $array['error'] = 'Fornecedor inexistente';
            return $array;
        }


        return $array;
    }

    public function update($id, Request $request){
        $array = ['error'];

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'phone'=>'required|digits:11',
            'cnpj'=>'required|digits:14|unique:providers,cnpj',
            'email'=>'required|email|unique:providers,email',
            'address'=>'required'
        ]);

        if(!$validator->fails()){
            $name = $request->input('name');
            $phone = $request->input('phone');
            $cnpj = $request->input('cnpj');
            $email = $request->input('email');
            $address = $request->input('address');

            $provider = Provider::find($id);
            if($provider){
                $provider->name = $name;
                $provider->phone = $phone;
                $provider->cnpj = $cnpj;
                $provider->email = $email;
                $provider->address = $address;
                $provider->save();

                $array['provider'] = $provider;

            }else{
                $array['error'] = 'Fornecedor inexistente.';
            }
        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function delete($id){
        $array = ['error'=>''];

        $provider = Provider::find($id);
        if($provider){
            $provider->delete();

            $array['success'] = 'Fornecedor deletado com sucesso.';
        }else{
            $array['error'] = 'Fornecedor inexistente.';
            return $array;
        }


        return $array;
    }
}
