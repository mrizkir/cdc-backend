<?php

namespace App\Http\Controllers\DMaster;

use App\Http\Controllers\Controller;
use App\Models\DMaster\FasilitasKarantinaModel;
use Illuminate\Http\Request;

class FasilitasKarantinaController extends Controller
{   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $this->hasPermissionTo('FASILITAS KARANTINA_BROWSE');
        
        $data = FasilitasKarantinaModel::all();
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'fasilitaskarantina'=>$data,
                                'message'=>'Fetch data fasilitas karantina berhasil diperoleh'
                            ],200);  

    }    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        // $this->hasPermissionTo('FASILITAS KARANTINA_STORE'); 
        $this->validate($request, [
            'Nm_Fasilitas'=>'required|unique:tmFasilitasKarantina',
            'alamat'=>'required',                     
        ]);

       
        $fasilitaskarantina = FasilitasKarantinaModel::create ([
            'FasilitasKarantinaID'=> uniqid ('uid'),
            'Nm_Fasilitas' => $request->input('Nm_Fasilitas'),
            'alamat' => $request->input('alamat'),            
            'PmKecamatanID' => $request->input('PmKecamatanID'),            
            'Nm_Kecamatan' => $request->input('Nm_Kecamatan'),            
            'PmDesaID' => $request->input('PmDesaID'),            
            'Nm_Desa' => $request->input('Nm_Desa'),            
        ]);     
        
        return Response()->json([
                                'status'=>1,
                                'pid'=>'store',
                                'fasilitaskarantina'=>$fasilitaskarantina,                                    
                                'message'=>'Data Fasilitas Karantina berhasil disimpan.'
                            ],200); 
               
    } 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$uuid)
    {        
        // $this->hasPermissionTo('FASILITAS KARANTINA_UPDATE'); 
        $fasilitaskarantina = FasilitasKarantinaModel::find($uuid);
        
        $this->validate($request, [
            'Nm_Fasilitas'=>'required',
            'alamat'=>'required',            
        ]);

        $fasilitaskarantina->Nm_Fasilitas = $request->input('Nm_Fasilitas');
        $fasilitaskarantina->alamat = $request->input('alamat');
        $fasilitaskarantina->PmKecamatanID=$request->input('PmKecamatanID');
        $fasilitaskarantina->Nm_Kecamatan=$request->input('Nm_Kecamatan');
        $fasilitaskarantina->PmDesaID=$request->input('PmDesaID');
        $fasilitaskarantina->Nm_Desa=$request->input('Nm_Desa');
        $fasilitaskarantina->save();

        return Response()->json([
                                'status'=>1,
                                'pid'=>'store',
                                'fasilitaskarantina'=>$fasilitaskarantina,                                    
                                'message'=>'Data Fasilitas Karantina berhasil diubah.'
                            ],200); 
                                
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$uuid)
    {  
        // $this->hasPermissionTo('FASILITAS KARANTINA_DESTROY'); 
        $fasilitaskarantina = FasilitasKarantinaModel::find($uuid);
        $result=$fasilitaskarantina->delete();
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'destroy',                
                                    'message'=>"Nama Fasilitas Karantina berhasil dihapus"
                                ],200);
    }
}
