<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Overtimes;
use App\Models\Employee;
use Carbon\Carbon;

class OvertimesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function createOvertime(Request $req){
        $validator = \Validator::make($req->all(), [
            'employee_id' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'time_started' => ['required', 'date_format:H:i'],
            'time_ended' => ['required', 'date_format:H:i' ,'after:time_started'],
        ]);
        if ($validator->fails()){
            return resError('Request required!',$validator->getMessageBag()->toArray());
        }

        $checkOvertime = Overtimes::checkOvertimeExist($req->employee_id,$req->date);
        if($checkOvertime){
            return resBadRequest('Overtime today already exist for employee!');
        }

        DB::beginTransaction();
        try {
            Overtimes::insert([
                'employee_id' => $req->employee_id,
                'date' => $req->date,
                'time_started' => $req->time_started,
                'time_ended' => $req->time_ended,
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

    public function calculateOvertime(Request $req){
        $validator = \Validator::make($req->all(), [
            'month' => ['required', 'date_format:Y-m'],
        ]);
        if ($validator->fails()){
            return resError('Request required!',$validator->getMessageBag()->toArray());
        }

        DB::beginTransaction();
        try {
            $overtime_duration_total = 0;
            $data = Employee::getEmployees();
            foreach ($data as $val) {
                $overTimes = Overtimes::getOvertimes($req->month,$val->id);
                $val->overtimes = $overTimes;
                if($overTimes){
                    foreach ($overTimes as $vals) {
                        $vals->overtime_duration = countOvertimeDuration($vals->time_started, $vals->time_ended);
                        $vals->time_started = Carbon::parse($vals->time_started)->format('H:i');
                        $vals->time_ended = Carbon::parse($vals->time_ended)->format('H:i');
                        $overtime_duration_total = $overtime_duration_total + $vals->overtime_duration;
                    }
                }
                $val->overtime_duration_total = $overtime_duration_total;
                $val->amount = amountOvertimes($val->salary,$overtime_duration_total);
            }
            return resSuccess('Success calculate overtimes!',$data);
        } catch (\Exception $e) {
            $commit=DB::rollback();
            return resError($e->getMessage());
        }
    }
}
