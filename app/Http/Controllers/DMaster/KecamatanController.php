<?php

namespace App\Http\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DMaster\KecamatanModel;
use App\Models\DMaster\DesaModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class KecamatanController extends Controller {        
    /**
     * digunakan untuk mendapatkan daftar kecamatan
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = KecamatanModel::orderBy('Nm_Kecamatan','ASC')
                                ->get();
                                
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'kecamatan'=>$data,
                                'message'=>'Fetch data kecamatan berhasil diperoleh'
                            ],200);  
    }
    public function desakecamatan (Request $request,$id)
    {
        
        $desa = DesaModel::where('PmKecamatanID',$id)
                            ->get();

        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',                                
                                'desa'=>$desa,
                                'message'=>'Fetch data desa dari kecamatan berhasil diperoleh'
                            ],200);  

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $this->hasPermissionTo('USERS PETUGAS_UPDATE');

        $kecamatan = KecamatanModel::find($id);
        
        if ($kecamatan == null)
        {
            return Response()->json([
                                    'status'=>0,
                                    'pid'=>'update',                
                                    'message'=>"Data Kecamatan tidak ditemukan"
                                ],422);         
        }
        else
        {
            $this->validate($request, [
                                        'lat'=>'required|numeric',
                                        'lat2'=>'required|numeric',            
                                        'lng'=>'required|numeric',
                                        'lng2'=>'required|numeric',                                                      
                                    ]); 
            $kecamatan->lat = $request->input('lat');
            $kecamatan->lat2 = $request->input('lat2');
            $kecamatan->lng = $request->input('lng');
            $kecamatan->lng2 = $request->input('lng2');
            $kecamatan->save();
            
            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'kecamatan'=>$kecamatan,      
                                    'message'=>'Data kecamatan berhasil diubah.'
                                ],200); 
        }    
    }
    /**
     * digunakan untuk mendapatkan daftar pasien berdasarkan kecamatan
     */
    public function pasien (Request $request,$id)
    {
        $kecamatan = KecamatanModel::find($id);
        
        if ($kecamatan == null)
        {
            return Response()->json([
                                    'status'=>0,
                                    'pid'=>'update',                
                                    'message'=>"Data Kecamatan tidak ditemukan"
                                ],422);         
        }
        else
        {
            $pasien = $kecamatan->pasien();
            
            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'pasien'=>$pasien,      
                                    'message'=>'Data pasien berdasarkan kecamatan '.$kecamatan->Nm_Kecamatan.' berhasil diperoleh.'
                                ],200); 
        }
    }
}