<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $fillable = ['name','salary'];

    public static function checkDuplicateByName($id){
        return Employee::where('name', $id)->first();
    }

    public static function getEmployees(){
        return Employee::select(['id','name','salary'])->get();
    }
}
