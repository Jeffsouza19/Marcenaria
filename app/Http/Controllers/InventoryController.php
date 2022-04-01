<?php

namespace App\Http\Controllers;

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

        return $array;
    }
}
