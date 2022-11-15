<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Employee;

class EmployeesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createEmployee(Request $req){
        $validator = \Validator::make($req->all(), [
            'name' => ['required', 'string', 'min:2'],
            'salary' => ['required', 'integer','min:7|max:8'],
        ]);
        if ($validator->fails()){
            return resError('Request required!',$validator->getMessageBag()->toArray());
        }
        if(!validateSalary($req->salary)){
            return resBadRequest('Salary cannot under 2 jt or above 10 jt!');
        }
        $checkName = Employee::checkDuplicateByName($req->name);
        if($checkName){
            return resBadRequest('Name already exist!');
        }

        DB::beginTransaction();
        try {
            Employee::insert([
                'name' => $req->name,
                'salary' => $req->salary,
                'created_at' => now(), 
                'updated_at' => now()
            ]);
            DB::commit();
            return resCreated('Data created!',$req->all());
        } catch (\Exception $e) {
            $commit=DB::rollback();
            return resError($e->getMessage());
        }
    }
}
