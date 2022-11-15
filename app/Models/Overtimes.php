<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Overtimes extends Model
{
    use HasFactory;
    protected $table = 'overtimes';
    protected $fillable = ['employee_id','date','time_started','time_ended'];

    public static function checkOvertimeExist($id,$date){
        return Overtimes::where('employee_id', $id)
        ->where('date', Carbon::parse($date)->format('Y-m-d'))
        ->first();
    }

    public static function getOvertimes($date,$id){
        return Overtimes::where('employee_id', $id)
        ->whereYear('date', Carbon::parse($date)->format('Y'))
        ->whereMonth('date', Carbon::parse($date)->format('m'))
        ->select(['id','date','time_started','time_ended'])
        ->get();
    }
}
