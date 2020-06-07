<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller {     
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function frontindex(Request $request)
    { 

        $subquery = \DB::table('users')
                            ->select(\DB::raw('status_pasien,COUNT(users.id) AS jumlah'))
                            ->join('model_has_roles','model_has_roles.model_id','users.id')
                            ->join('roles','roles.id','model_has_roles.role_id')
                            ->groupBy('status_pasien');

        $data=\DB::table('pasien_status')
                    ->select(\DB::raw('id_status,nama_status,COALESCE(jumlah,0)'))
                    ->leftJoinSub($subquery,'userspasien',function($join){
                        $join->on('userspasien.status_pasien','=','pasien_status.id_status');
                    })
                    ->orderBy('id_status','ASC')
                    ->get();
    

        
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'ringkasan'=>$data,
                                'message'=>'Fetch data dashboard berhasil diperoleh'
                            ],200);   
    }    
}