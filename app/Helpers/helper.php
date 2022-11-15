<?php

use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Models\Settings;
use App\Models\References;


function resSuccess($msg,$data=null){
    return response()->json([
        'status' => 'success',
        'message' => $msg,
        'data' => $data,
    ], 200);
}

function resCreated($msg,$data=null){
    return response()->json([
        'status' => 'success',
        'message' => $msg,
        'data' => $data,
    ], 201);
}

function resNotFound($msg){
    return response()->json([
        'status' => 'fail',
        'message' => $msg,
    ], 404);
}

function resFound($msg,$data=null){
    return response()->json([
        'status' => 'success',
        'message' => $msg,
        'data' => $data,
    ], 302);
}

function resError($msg,$data=null){
    return response()->json([
        'status' => 'fail',
        'message' => $msg,
        'data' => $data,
    ], 500);
}

function resBadRequest($msg){
    return response()->json([
        'status' => 'fail',
        'message' => $msg,
    ], 400);
}

function validateSalary($id){
    return $id < 2000000 || $id > 10000000 ? false : true;
}

function keySettings(){
    $data = Settings::first();
    return $data ? $data->key : null;
}

function getExpression(){
    $value = Settings::first();
    $data = References::where('id', $value->value)->first();
    return $data ? $data->expression : null;
}

function countOvertimeDuration($startTime,$endTime){
    $startTime = new Carbon($startTime);
    $endTime = new Carbon($endTime);
    $total = $endTime->diffInMinutes($startTime);
    return round($total / 105);
}

function amountOvertimes($salary,$overtimeDurationTotal){
    $settings = getExpression();
    $text_salary = '/salary/';
    $text_overtime_duration_total = '/overtime_duration_total/';
    $settings = preg_replace($text_salary, 'a', $settings);
    $settings = preg_replace($text_overtime_duration_total, 'b', $settings);
    return round(math_eval($settings, ['a' => $salary, 'b' => $overtimeDurationTotal]));
}