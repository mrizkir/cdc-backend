<?php

namespace App\Http\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DMaster\KecamatanModel;
use App\Models\DMaster\DesaModel;

class DesaController extends Controller {
    /**
     * digunakan untuk mendapatkan daftar kecamatan
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = DesaModel::orderBy('Nm_Desa','ASC')
                                ->get();
                                
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'desa'=>$data,
                                'message'=>'Fetch data desa berhasil diperoleh'
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

        $desa = DesaModel::find($id);
        
        if ($desa == null)
        {
            return Response()->json([
                                    'status'=>0,
                                    'pid'=>'update',                
                                    'message'=>"Data Desa tidak ditemukan"
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
           $desa->lat = $request->input('lat');
           $desa->lat2 = $request->input('lat2');
           $desa->lng = $request->input('lng');
           $desa->lng2 = $request->input('lng2');
           $desa->save();
            
            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'desa'=>$desa,      
                                    'message'=>'Data Desa berhasil diubah.'
                                ],200); 
        }    
    }
}