<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryType;
use App\Models\Provider;
use GuzzleHttp\RetryMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function add(Request $request){
        $array = ['error'=>''];

        $validator = Validator::make($request->all(), [
            'product'=>'required',
            'type'=>'required|size:2',
            'quantaty'=>'integer',
            'provider'=>'required',
        ]);

        if(!$validator->fails()){
            $product = $request->input('product');
            $type = $request->input('type');
            $quantaty = $request->input('quantaty');
            $provider = $request->input('provider');
            $description = $request->input('description');

            $idProvider = Provider::where('name', $provider)->exists();
            $idType = InventoryType::where('type',$type)->exists();


            if($idProvider){
                $findProvider = Provider::select('id')->where('name', $provider)->get();
                $idProvider = $findProvider[0]['id'];
                if($idType){
                    $findType = InventoryType::select('id')->where('type',$type)->get();
                    $idType = $findType[0]['id'];
                }else{
                   $newType = new InventoryType();
                   $newType->type = $type;
                   $newType->save();

                   $idType = $newType['id'];

                }
            }else{
                $array['error'] = 'Fornecedor não está cadastrado.';
                return $array;
            }



            $newProduct = new Inventory();
            $newProduct->product = $product;
            $newProduct->id_inventoryTypes = $idType;
            $newProduct->quantaty = $quantaty;
            $newProduct->id_provider = $idProvider;
            $newProduct->description = $description;
            $newProduct->save();

            $array['success'] = 'Produto salvo com sucesso';
            $array['product'] = $newProduct;

        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }


    public function getAll(){
        $array = ['error' => ''];

        $all = Inventory::all();
        if($all){
            $array['inventory'] = $all;
        }else{
            $array['error'] = 'Não há produtos cadastrados no estoque.';
        }

        return $array;
    }

    public function update($id, Request $request){
        $array = ['error' => ''];

        $pdtInvetory = Inventory::find($id);


        $validator= Validator::make($request->all(),[
            'quantaty'=>'integer'
        ]);
        if($pdtInvetory){
            $pdtInvetory = Inventory::where('id', $id)->get();
            $pdtInvetory = $pdtInvetory[0];
            if(!$validator->fails()){
                $quantaty = intval($request->input('quantaty'));
                $description = $request->input('description');
                $type = intval($request->input('type'));

                if($quantaty || $description || $type){
                    if($quantaty){
                        $pdtInvetory->quantaty = $quantaty;
                    }
                    if($description){
                        $pdtInvetory->description = $description;
                    }
                    if($type){
                        $pdtInvetory->id_inventoryTypes = $type;
                    }
                    $pdtInvetory->save();
                    $array['product'] = $pdtInvetory;

                }else{
                    $array['error'] = 'Não houve alteraçoes';
                    $array['product'] = $pdtInvetory;
                }
            }else{
                $array['error'] = $validator->errors()->first;
            }
        }else{
            $array['error'] = 'Produto não encontrado';
        }
        return $array;
    }

    public function delete($id){
        $array = ['error'=>''];

        $pdtInvetory = Inventory::find($id);

        if($pdtInvetory){
            $pdtInvetory->delete();
            $array['error'] = 'deletado com sucesso';
        }else{
            $array['error'] = 'Produto não encontrado';
        }

        return $array;
    }
}
