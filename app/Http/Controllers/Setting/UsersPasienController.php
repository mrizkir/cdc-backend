<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Rules\IgnoreIfDataIsEqualValidation;
use App\Models\User;
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
        $data = User::role('pasien')->get();
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'userspasien'=>$data,
                                'message'=>'Fetch data users Pasien berhasil diperoleh'
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
            'nomor_hp'=>'required',                
            'alamat'=>'required',            
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
            'nomor_hp'=>$request->input('nomor_hp'),
            'alamat'=>$request->input('alamat'),
            'PmKecamatanID'=>$request->input('PmKecamatanID'),
            'Nm_Kecamatan'=>$request->input('Nm_Kecamatan'),
            'PmDesaID'=>$request->input('PmDesaID'),
            'Nm_Desa'=>$request->input('Nm_Desa'),
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $this->hasPermissionTo('RKA MURNI_SHOW');
        $user = User::find($id);

        if (is_null($user))
        {
            return Response()->json([
                                'status'=>0,
                                'pid'=>'destroy',                
                                'message'=>"Data Pasien tidak ditemukan"
                            ],422);   
        }
        else
        {

            return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'user'=>$user,                             
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
                                    'pid'=>'destroy',                
                                    'message'=>"Data Pasien tidak ditemukan"
                                ],422);         
        }
        else
        {
            $this->validate($request, [
                                'username'=>['required',new IgnoreIfDataIsEqualValidation('users',$user->username)],           
                                'name'=>'required',                
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