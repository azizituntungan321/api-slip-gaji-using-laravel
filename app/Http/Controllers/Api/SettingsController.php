<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\References;
use App\Models\Settings;

class SettingsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function patchSetting(Request $req){
        $validator = \Validator::make($req->all(), [
            // ADA DUA OPSI
            // BISA JUGA MENGGUNAKAN EXIST UNTUK CHECK DATA EXIST DI TABLE REFERENCES
            // 'key' => ['required', 'string', 'exists:references,code'],
            // 'value' => ['required', 'string', 'exists:references,id'],

            'key' => ['required', 'string'],
            'value' => ['required', 'string'],
        ]);
        if ($validator->fails()){
            return resError('Request required!',$validator->getMessageBag()->toArray());
        }

        if(keySettings() != $req->key){
            return resBadRequest('Key is not found in references!');
        }

        $checkId = References::checkCode($req->value);
        if(!$checkId){
            return resBadRequest('Value not found in references!');
        }

        DB::beginTransaction();
        try {
            Settings::where('key', keySettings())->update([
                'value' => $req->value,
            ]);
            DB::commit();
            return resSuccess('Data updated!',$req->all());
        } catch (\Exception $e) {
            $commit=DB::rollback();
            return resError($e->getMessage());
        }
    }
    
}
