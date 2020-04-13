<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Statistik1Model;
use App\Models\DMaster\OrganisasiModel;

class DashboardController extends Controller {     
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function frontindex(Request $request)
    {                
        $tahun=$request->input('tahun');
        $this->validate($request, [            
            'tahun'=>'required',            
        ]);        
        $data = Statistik1Model::find($tahun);
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'statistik1'=>$data,
                                'message'=>'Fetch data dashboard berhasil diperoleh'
                            ],200);    
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminindex(Request $request)
    {              
        $tahun=$request->input('tahun');
        $this->validate($request, [            
            'tahun'=>'required',            
        ]);
        
        $user = $this->guard()->user();
        $statistik=[];
        if ($user->hasRole('superadmin') || $user->hasRole('bapelitbang'))
        {
            $role='bapelitbang';
            $data = Statistik1Model::find($tahun);
        }
        else if ($user->hasRole('opd'))
        {
            $role='opd';
            $statistik = OrganisasiModel::where('TA',$tahun)
                                        ->whereIn('OrgID',json_decode($user->payload,true))
                                        ->get();
            $jumlah_opd = $statistik->count();
            $data['PaguDana1']=$statistik->sum('PaguDana1');
            $data['PaguDana2']=$statistik->sum('PaguDana2');
            $data['JumlahProgram1']=$statistik->sum('JumlahProgram1');
            $data['JumlahProgram2']=$statistik->sum('JumlahProgram1');
            $data['JumlahKegiatan1']=$statistik->sum('JumlahKegiatan1');
            $data['JumlahKegiatan2']=$statistik->sum('JumlahKegiatan2');
            $data['RealisasiKeuangan1']=$statistik->sum('RealisasiKeuangan1');
            $data['RealisasiKeuangan2']=$statistik->sum('RealisasiKeuangan2');
            $data['RealisasiFisik1']=$statistik->sum('RealisasiFisik1');
            $data['RealisasiFisik2']=$statistik->sum('RealisasiFisik2');
        }
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',                                                          
                                'role_dashboard'=>$role,
                                'statistik1'=>$data,
                                'daftar_opd'=>$statistik,
                                'OrgID_Selected'=>$user->payload,
                                'message'=>'Fetch data dashboard berhasil diperoleh'
                            ],200);    
        
    }
}