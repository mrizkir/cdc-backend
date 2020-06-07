<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportPasienController extends Controller 
{
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {             
        // $this->hasPermissionTo('REPORT PASIEN ALL');

    }
    public function printtoexcel (Request $request)
    {
        // $this->hasPermissionTo('FORM A MURNI_BROWSE');

        // $this->validate($request, [                            
        //     'no_bulan'=>'required',   
        //     'tahun'=>'required',   
        //     'SOrgID'=>'required',   
        //     'RKAID'=>'required|exists:trRKA,RKAID',            
        // ]);     
        // $SOrgID = $request->input('SOrgID');
        // $no_bulan = $request->input('no_bulan');
        // $tahun = $request->input('tahun');
        // $RKAID = $request->input('RKAID');

        // $unitkerja = SubOrganisasiModel::find($SOrgID);        
        // $data_report=[
        //                 'RKAID'=>$RKAID,
        //                 'kode_subkegiatan'=>$unitkerja->kode_subkegiatan,
        //                 'SOrgNm'=>$unitkerja->SOrgNm,                        
        //                 'no_bulan'=>$no_bulan,
        //                 'tahun'=>$tahun,
        //                 'nama_pengguna_anggaran'=>$unitkerja->NamaKepalaUnitKerja,
        //                 'nip_pengguna_anggaran'=>$unitkerja->NIPKepalaUnitKerja
        //             ];
        // $report= new \App\Models\Report\FormAMurniModel ($data_report);
        // $generate_date=date('Y-m-d_H_m_s');
        // return $report->download("forma_a_$generate_date.xlsx");
    }

}