<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Rules\IgnoreIfDataIsEqualValidation;
use App\Models\User;
use App\Models\Pasien\PasienDetailModel;
use App\Models\Setting\HistoryPasienModel;
use App\Models\Setting\LokasiPasienModel;
use App\Helpers\Helper;
use Spatie\Permission\Models\Role;

class UsersPasienController extends Controller {         
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {           
        // $this->hasPermissionTo('USERS PASIEN_BROWSE');        
        $data = User::role('pasien')
                ->select(\DB::raw('id,username,name,tempat_lahir,tanggal_lahir,nomor_hp,alamat,"PmKecamatanID","Nm_Kecamatan","PmDesaID","Nm_Desa","foto","status_pasien","nama_status","payload","created_at","updated_at"'))
                ->join('pasien_status','pasien_status.id_status','users.status_pasien')
                ->orderBy('name','ASC')
                ->orderBy('created_at','ASC')
                ->get();
        
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'userspasien'=>$data,
                                'message'=>'Fetch data users Pasien berhasil diperoleh'
                            ],200);  
    }    
    /**
     * lokasi terakhir
     *
     * @return \Illuminate\Http\Response
     */
    public function lokasiterakhir(Request $request)
    {           
        // $this->hasPermissionTo('USERS PASIEN_BROWSE');  
        $this->validate($request, [
                        'mode'=>'required',
                        'id'=>'required',            
            ]
        );
        switch($mode)
        {
            default :
                $data = \DB::table('v_lokasi_terakhir')
                        ->get();
        }
        
        $data->transform(function ($item,$key){
            $item->usia=Helper::getUsia($item->tanggal_lahir);            
            return $item;
        });

        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'lokasiterakhir'=>$data,
                                'message'=>'Fetch data lokasi terakhir berhasil diperoleh'
                            ],200);  
    }
    /**
     * lokasi terakhir
     *
     * @return \Illuminate\Http\Response
     */
    public function lokasiterakhirpublik(Request $request)
    {   
        $this->validate($request, [
                        'mode'=>'required',
                        'id'=>'required',            
            ]
        );
        switch($mode)
        {
            default :
                $data = \DB::table('v_lokasi_terakhir')
                        ->select(\DB::raw('"PmKecamatanID","Nm_Kecamatan","PmDesaID","Nm_Desa","status_pasien",nama_status,lat,lng,updated_at'))
                        ->get();
        }
        

        
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'lokasiterakhir'=>$data,
                                'message'=>'Fetch data lokasi terakhir berhasil diperoleh'
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
        // $this->hasPermissionTo('USERS PASIEN_STORE');

        $this->validate($request, [
            'username'=>'required|string|unique:users',
            'password'=>'required',            
            'name'=>'required',                
            'tempat_lahir'=>'required',                
            'tanggal_lahir'=>'required',                
            'jk'=>'required',                
            'gol_darah'=>'required',                
            'nomor_hp'=>'required',                
            'alamat'=>'required',            
            'status_pasien'=>'required',            
            'PmKecamatanID'=>'required',            
            'Nm_Kecamatan'=>'required',            
            'PmDesaID'=>'required',            
            'Nm_Desa'=>'required',            
            'foto'=>'required',               
        ]);
        
        $now = \Carbon\Carbon::now()->toDateTimeString();        
        $user=User::create([
            'username'=> $request->input('username'),
            'password'=>Hash::make($request->input('password')),            
            'name'=>$request->input('name'),
            'tempat_lahir'=>$request->input('tempat_lahir'),
            'tanggal_lahir'=>$request->input('tanggal_lahir'),
            'jk'=>$request->input('jk'),
            'gol_darah'=>$request->input('gol_darah'),
            'nomor_hp'=>$request->input('nomor_hp'),
            'alamat'=>$request->input('alamat'),
            'PmKecamatanID'=>$request->input('PmKecamatanID'),
            'Nm_Kecamatan'=>$request->input('Nm_Kecamatan'),
            'PmDesaID'=>$request->input('PmDesaID'),
            'Nm_Desa'=>$request->input('Nm_Desa'),
            'status_pasien'=>$request->input('status_pasien'),  
            'foto'=>$request->input('foto'),
            'payload'=>$request->input('payload'),            
            'created_at'=>$now, 
            'updated_at'=>$now
        ]);            
        $role='pasien';   
        $user->assignRole($role);      

        \App\Models\Setting\ActivityLog::log($request,[
                                        'object' => $this->guard()->user(), 
                                        'user_id' => $this->guard()->user()->id, 
                                        'message' => 'Menambah user Pasien('.$user->username.') berhasil'
                                    ]);
            
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'store',
                                    'user'=>$user,                                    
                                    'message'=>'Data user Pasien berhasil disimpan.'
                                ],200); 

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storedetail(Request $request,$id)
    {
        // $this->hasPermissionTo('USERS PASIEN_STORE');
        $user = User::find($id);
        
        if ($user == null)
        {
            return Response()->json([
                                    'status'=>0,
                                    'pid'=>'store',                
                                    'message'=>"Data Pasien tidak ditemukan"
                                ],422);         
        }
        else
        {
            $this->validate($request, [                
                'founded_alamat'=>'required|string',
                'founded_PmKecamatanID'=>'required',            
                'founded_Nm_Kecamatan'=>'required',                
                'founded_PmDesaID'=>'required',                
                'founded_Nm_Desa'=>'required',                
                'founded_lat'=>'required',                
                'founded_lng'=>'required',  

                'FasilitasKarantinaID'=>'required',                
                'karantina_alamat'=>'required',            
                'karantina_PmKecamatanID'=>'required',            
                'karantina_Nm_Kecamatan'=>'required',            
                'karantina_PmDesaID'=>'required',            
                'karantina_Nm_Desa'=>'required', 
                'karantina_mulai'=>'required', 
                'karantina_selesai'=>'required', 
                'karantina_time'=>'required', 

                'transmisi_penularan'=>'required', 
                'ket_transmisi'=>'required', 

                'jenis_test'=>'required', 
                
            ]);
            
            $now = \Carbon\Carbon::now()->toDateTimeString();        
            $detail=PasienDetailModel::create([
                'user_id'=>$user->id,
                'founded_alamat'=> $request->input('founded_alamat'),
                'founded_PmKecamatanID'=>$request->input('founded_PmKecamatanID'),            
                'founded_Nm_Kecamatan'=>$request->input('founded_Nm_Kecamatan'),
                'founded_PmDesaID'=>$request->input('founded_PmDesaID'),
                'founded_Nm_Desa'=>$request->input('founded_Nm_Desa'),
                'founded_lat'=>$request->input('founded_lat'),
                'founded_lng'=>$request->input('founded_lng'),

                'FasilitasKarantinaID'=>$request->input('FasilitasKarantinaID'),
                'karantina_alamat'=>$request->input('karantina_alamat'),
                'karantina_PmKecamatanID'=>$request->input('karantina_PmKecamatanID'),
                'karantina_Nm_Kecamatan'=>$request->input('karantina_Nm_Kecamatan'),
                'karantina_PmDesaID'=>$request->input('karantina_PmDesaID'),
                'karantina_Nm_Desa'=>$request->input('karantina_Nm_Desa'),
                'karantina_mulai'=>$request->input('karantina_mulai'),
                'karantina_selesai'=>$request->input('karantina_selesai'),
                'karantina_time'=>$request->input('karantina_time'),

                'transmisi_penularan'=>$request->input('transmisi_penularan'),            
                'ket_transmisi'=>$request->input('ket_transmisi'),            
                'jenis_test'=>$request->input('jenis_test'),    
                        
                'created_at'=>$now, 
                'updated_at'=>$now
            ]);            

            \App\Models\Setting\ActivityLog::log($request,[
                                            'object' => $this->guard()->user(), 
                                            'user_id' => $this->guard()->user()->id, 
                                            'message' => 'Menambah detail pasien ('.$user->username.') berhasil'
                                        ]);
                
            return Response()->json([
                                        'status'=>1,
                                        'pid'=>'store',
                                        'user'=>$user,                                    
                                        'detail'=>$detail,                                    
                                        'message'=>'Data detail pasien  berhasil disimpan.'
                                    ],200); 
        }

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatedetail(Request $request,$id)
    {
        // $this->hasPermissionTo('USERS PASIEN_STORE');
        $user = User::find($id);
        
        if ($user == null)
        {
            return Response()->json([
                                    'status'=>0,
                                    'pid'=>'update',                
                                    'message'=>"Data Pasien tidak ditemukan"
                                ],422);         
        }
        else
        {
            $this->validate($request, [                
                'founded_alamat'=>'required|string',
                'founded_PmKecamatanID'=>'required',            
                'founded_Nm_Kecamatan'=>'required',                
                'founded_PmDesaID'=>'required',                
                'founded_Nm_Desa'=>'required',                
                'founded_lat'=>'required',                
                'founded_lng'=>'required',  

                'FasilitasKarantinaID'=>'required',                
                'karantina_alamat'=>'required',            
                'karantina_PmKecamatanID'=>'required',            
                'karantina_Nm_Kecamatan'=>'required',            
                'karantina_PmDesaID'=>'required',            
                'karantina_Nm_Desa'=>'required', 
                'karantina_mulai'=>'required', 
                'karantina_selesai'=>'required', 
                'karantina_time'=>'required', 

                'transmisi_penularan'=>'required', 
                'ket_transmisi'=>'required', 

                'jenis_test'=>'required', 
                
            ]);
            
            $detail=PasienDetailModel::find($id);

            $detail->founded_alamat= $request->input('founded_alamat');            
            $detail->founded_PmKecamatanID=$request->input('founded_PmKecamatanID');            
            $detail->founded_Nm_Kecamatan=$request->input('founded_Nm_Kecamatan');
            $detail->founded_PmDesaID=$request->input('founded_PmDesaID');
            $detail->founded_Nm_Desa=$request->input('founded_Nm_Desa');
            $detail->founded_lat=$request->input('founded_lat');
            $detail->founded_lng=$request->input('founded_lng');

            $detail->FasilitasKarantinaID=$request->input('FasilitasKarantinaID');
            $detail->karantina_alamat=$request->input('karantina_alamat');
            $detail->karantina_PmKecamatanID=$request->input('karantina_PmKecamatanID');
            $detail->karantina_Nm_Kecamatan=$request->input('karantina_Nm_Kecamatan');
            $detail->karantina_PmDesaID=$request->input('karantina_PmDesaID');
            $detail->karantina_Nm_Desa=$request->input('karantina_Nm_Desa');
            $detail->karantina_mulai=$request->input('karantina_mulai');
            $detail->karantina_selesai=$request->input('karantina_selesai');
            $detail->karantina_time=$request->input('karantina_time');

            $detail->transmisi_penularan=$request->input('transmisi_penularan');            
            $detail->ket_transmisi=$request->input('ket_transmisi');            
            $detail->jenis_test=$request->input('jenis_test');                
            
            $detail->save();
            
            \App\Models\Setting\ActivityLog::log($request,[
                                            'object' => $this->guard()->user(), 
                                            'user_id' => $this->guard()->user()->id, 
                                            'message' => 'Mengubah detail pasien ('.$user->username.') berhasil'
                                        ]);
                
            return Response()->json([
                                        'status'=>1,
                                        'pid'=>'update',
                                        'user'=>$user,                                    
                                        'detail'=>$detail,                                    
                                        'message'=>'Data detail pasien  berhasil diubah.'
                                    ],200); 
        }

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $this->hasPermissionTo('USERS PASIEN_SHOW');

        $user = User::select(\DB::raw('
                        id,
                        username,
                        name,
                        tempat_lahir,
                        tanggal_lahir,
                        jk,
                        gol_darah,
                        nomor_hp,
                        alamat,
                        "PmKecamatanID",
                        "Nm_Kecamatan",
                        "PmDesaID",
                        "Nm_Desa",

                        "founded_alamat",
                        "founded_alamat",
                        "founded_PmKecamatanID",
                        "founded_Nm_Kecamatan",
                        "founded_PmDesaID",
                        "founded_Nm_Desa",
                        "founded_lat",
                        "founded_lng",

                        "FasilitasKarantinaID",
                        "karantina_alamat",            
                        "karantina_PmKecamatanID",
                        "karantina_Nm_Kecamatan",
                        "karantina_PmDesaID",
                        "karantina_Nm_Desa",
                        
                        "karantina_mulai",
                        "karantina_selesai",            
                        "karantina_time",

                        "transmisi_penularan",
                        "ket_transmisi",
                        
                        "jenis_test",
                        
                        "foto",
                        "status_pasien",
                        "nama_status",
                        "payload",
                        users.created_at,
                        users.updated_at'
                    ))
                    ->join('pasien_status','pasien_status.id_status','users.status_pasien')
                    ->leftJoin('pasien_detail','pasien_detail.user_id','users.id')
                    ->find($id);
        
        if (is_null($user))
        {
            return Response()->json([
                                'status'=>0,
                                'pid'=>'fetchdata',                
                                'message'=>"Data Pasien tidak ditemukan"
                            ],422);   
        }
        else
        {  
            
            $history=HistoryPasienModel::where('user_id',$user->id)
                                        ->get();

            $lokasi=LokasiPasienModel::where('user_id',$user->id)  
                                        ->orderBy('id','DESC')
                                        ->limit(5)                                      
                                        ->get();

            $user=$user->toArray();
            $user['usia']=Helper::getUsia($user['tanggal_lahir']);
            return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'user'=>$user,         
                                'history'=>$history,
                                'lokasi'=>$lokasi,                    
                                'message'=>'Fetch data pasien berhasil diperoleh'
                            ],200); 
        }            
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
        // $this->hasPermissionTo('USERS PASIEN_UPDATE');

        $user = User::find($id);
        
        if ($user == null)
        {
            return Response()->json([
                                    'status'=>0,
                                    'pid'=>'update',                
                                    'message'=>"Data Pasien tidak ditemukan"
                                ],422);         
        }
        else
        {
            $this->validate($request, [
                                'username'=>['required',new IgnoreIfDataIsEqualValidation('users',$user->username)],           
                                'name'=>'required',      
                                'tempat_lahir'=>'required',                
                                'tanggal_lahir'=>'required',     
                                'jk'=>'required',                
                                'gol_darah'=>'required',                     
                                'nomor_hp'=>'required',                
                                'alamat'=>'required',            
                                'PmKecamatanID'=>'required',            
                                'Nm_Kecamatan'=>'required',            
                                'PmDesaID'=>'required',            
                                'Nm_Desa'=>'required',            
                                'foto'=>'required',               
                            ]); 
            
            $user->username = $request->input('username');
            if (!empty(trim($request->input('password')))) {
                $user->password = Hash::make($request->input('password'));
            } 
            $user->name = $request->input('name');
            $user->tempat_lahir = $request->input('tempat_lahir');
            $user->tanggal_lahir = $request->input('tanggal_lahir');
            $user->jk = $request->input('jk');
            $user->gol_darah = $request->input('gol_darah');
            $user->nomor_hp = $request->input('nomor_hp');
            $user->alamat = $request->input('alamat');
            $user->email = $request->input('email');
            $user->PmKecamatanID=$request->input('PmKecamatanID');
            $user->Nm_Kecamatan=$request->input('Nm_Kecamatan');
            $user->PmDesaID=$request->input('PmDesaID');
            $user->Nm_Desa=$request->input('Nm_Desa');
            $user->foto=$request->input('foto');
            $user->payload=$request->input('payload');
            $user->updated_at = \Carbon\Carbon::now()->toDateTimeString();
            $user->save();

            \App\Models\Setting\ActivityLog::log($request,[
                                                        'object' => $this->guard()->user(), 
                                                        'user_id' => $this->guard()->user()->id, 
                                                        'message' => 'Mengubah data user Pasien('.$user->username.') berhasil'
                                                    ]);

            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'user'=>$user,      
                                    'message'=>'Data user Pasien '.$user->username.' berhasil diubah.'
                                ],200); 
        
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatestatus(Request $request, $id)
    {
        // $this->hasPermissionTo('USERS PASIEN_UPDATE');

        $user = User::find($id);
        
        if ($user == null)
        {
            return Response()->json([
                                    'status'=>0,
                                    'pid'=>'update',                
                                    'message'=>"Data Pasien tidak ditemukan"
                                ],422);         
        }
        else
        {
            $this->validate($request, [
                                'status_pasien'=>['required'],                                                        
                            ]); 

            $status_pasien=$request->input('status_pasien');
            $status_lama = Helper::getStatusPasien($user->status_pasien);

            $user->status_pasien=$status_pasien;
            $payload=json_decode($user->payload,true);
            $payload['status_pasien']=Helper::getStatusPasien($status_pasien);
            $user->payload=json_encode($payload);
            $user->updated_at = \Carbon\Carbon::now()->toDateTimeString();
            $user->save();
            
            $now = \Carbon\Carbon::now()->toDateTimeString();        
            
            HistoryPasienModel::create([
                                        'user_id'=>$user->id,
                                        'id_status'=>$status_pasien,
                                        'nama_status'=>Helper::getStatusPasien($status_pasien),
                                        'Descr'=>'Status berubah menjadi '.Helper::getStatusPasien($status_pasien),
                                        'created_at'=>$now, 
                                        'updated_at'=>$now
                                    ]);
    
            \App\Models\Setting\ActivityLog::log($request,[
                                                        'object' => $this->guard()->user(), 
                                                        'user_id' => $this->guard()->user()->id, 
                                                        'message' => 'Mengubah status Pasien ('.$user->username.') dari '.$status_lama.' menjadi ' . Helper::getStatusPasien($status_pasien).' berhasil'
                                                    ]);

            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'user'=>$user,      
                                    'message' => 'Mengubah status Pasien ('.$user->username.') dari '.$status_lama.' menjadi ' . Helper::getStatusPasien($status_pasien).' berhasil'
                                ],200); 
        
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tambahlokasi(Request $request, $id)
    {
        // $this->hasPermissionTo('USERS PASIEN_UPDATE');

        $user = User::find($id);
        
        if ($user == null)
        {
            return Response()->json([
                                    'status'=>0,
                                    'pid'=>'store',                
                                    'message'=>"Data Pasien tidak ditemukan"
                                ],422);         
        }
        else
        {
            $this->validate($request, [
                                'lat'=>['required'],                                                        
                                'lng'=>['required'],                                                        
                            ]); 

            $now = \Carbon\Carbon::now()->toDateTimeString();        
            $lokasi=LokasiPasienModel::create([
                                    'user_id'=>$user->id,
                                    'lat'=>$request->input('lat'),
                                    'lng'=>$request->input('lng'),                                    
                                    'created_at'=>$now, 
                                    'updated_at'=>$now
                                ]);
                                
            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'store',
                                    'lokasi'=>$lokasi,      
                                    'message' => 'Menambah Lokasi Koordinate Pasien berhasil'
                                ],200); 
        
        }
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    { 
        // $this->hasPermissionTo('USERS PASIEN_DESTROY');

        $user = User::where('isdeleted','t')
                    ->find($id); 
        
        if ($user == null)
        {
            return Response()->json([
                                    'status'=>0,
                                    'pid'=>'destroy',                
                                    'message'=>"Data Pasien tidak ditemukan."
                                ],422);         
        }
        else
        {
            $username=$user->username;
            $user->delete();

            \App\Models\Setting\ActivityLog::log($request,[
                                                            'object' => $this->guard()->user(), 
                                                            'user_id' => $this->guard()->user()->id, 
                                                            'message' => 'Menghapus user Pasien('.$username.') berhasil'
                                                        ]);
            
            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'destroy',                
                                    'message'=>"User Pasien ($username) berhasil dihapus"
                                ],200);         
        }
        
                  
    }
}