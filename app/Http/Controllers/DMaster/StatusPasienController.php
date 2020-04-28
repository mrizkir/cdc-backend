<?php

namespace App\Http\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DMaster\StatusPasienModel;

class StatusPasienController extends Controller {        
    /**
     * digunakan untuk mendapatkan daftar kecamatan
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = StatusPasienModel::all();
                                
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'statuspasien'=>$data,
                                'message'=>'Fetch data status pasien berhasil diperoleh'
                            ],200);  
    }
   
}