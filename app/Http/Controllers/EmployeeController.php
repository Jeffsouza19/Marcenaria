<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function add(Request $request){
        $array = ['error'=>''];

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'phone'=>'required|digits:11',
            'email'=>'required|email|unique:employees,email',
            'cpf'=>'required|unique:employees,cpf|digits:11',
            'address'=>'required',
            'occupation'=>'required',
            'salary'=>'required|integer',
            'password'=>'required',
            'password_confirm'=>'required|same:password'
        ]);

        if(!$validator->fails()){
            $name = $request->input('name');
            $phone = $request->input('phone');
            $email = $request->input('email');
            $cpf = $request->input('cpf');
            $address = $request->input('address');
            $occupation = $request->input('occupation');
            $salary = $request->input('salary');
            $password = $request->input('password');
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $employee = new Employee();
            $employee->name = $name;
            $employee->phone = $phone;
            $employee->email = $email;
            $employee->cpf = $cpf;
            $employee->address = $address;
            $employee->occupation = $occupation;
            $employee->salary = $salary;
            $employee->password = $hash;
            $employee->save();

            $array['employee'] = $employee;
            $array['success'] = 'Colaborador cadastrado com sucesso';

        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function getAll(){
        $array = ['error'=>''];

        $employees = Employee::select('name', 'phone', 'occupation', 'avatar')->get();
        if($employees){
            $array['employees'] = $employees;
        }else{
            $array['error'] = 'Não há colaborador cadastrado.';
            return $array;
        }

        return $array;
    }

    public function getOne($id){
        $array = ['error'=>''];

        $employee = Employee::find($id);
        if($employee){
            $employee = Employee::where('id', $id)->get();

            $array['employee'] = $employee;
        }else{
            $array['error'] = 'Colaborador inexistente';
            return $array;
        }

        return $array;
    }

    public function update($id, Request $request){
        $array = ['error'=>''];

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'phone'=>'required|digits:11',
            'email'=>'email|unique:employees,email',
            'address'=>'required',
            'occupation'=>'required',
            'salary'=>'required|integer',
        ]);

        if(!$validator->fails()){
            $name = $request->input('name');
            $phone = $request->input('phone');
            $address = $request->input('address');
            $occupation = $request->input('occupation');
            $salary = $request->input('salary');

            $employee = Employee::find($id);
            if($employee){
                $employee->name = $name;
                $employee->phone = $phone;
                $employee->address = $address;
                $employee->occupation = $occupation;
                $employee->salary = $salary;
                $employee->save();

                $array['success'] = 'Colaborador atualizado';
                $array['employee'] = $employee;
            }else{
                $array['error'] = 'Colaborador inexistente';
            }
        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function delete($id){
        $array = ['error'=>''];

        $employee = Employee::find($id);

        if($employee){
            $employee->delete();
            $array['success'] = 'Colaborador deletado com sucesso';
        }else{
            $array['error'] = 'Colaborador inexistente';
        }

        return $array;
    }
}
