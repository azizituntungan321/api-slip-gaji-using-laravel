<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class References extends Model
{
    use HasFactory;
    protected $table = 'references';
    protected $fillable = ['code','name','expression'];

    public static function checkCode($id){
        return References::where('code', keySettings())
        ->where('id', $id)
        ->first();
    }
}
