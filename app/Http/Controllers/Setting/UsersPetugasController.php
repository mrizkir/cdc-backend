<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Rules\IgnoreIfDataIsEqualValidation;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersPetugasController extends Controller {         
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {           
        // $this->hasPermissionTo('USERS PETUGAS_BROWSE');

        $user=$this->guard()->user();
        if ($user->hasRole(['superadmin','petugas']))
        {
            $data = User::role('petugas')->get();
        }       
        else if ($user->hasRole('petugas'))
        {
            $daftar_petugas=json_decode($user->payload,true);
            $data = User::role('petugas')->where(function ($query) use ($daftar_petugas){
                for ($i = 0; $i < count($daftar_petugas); $i++){
                    $query->orwhere('payload', 'ilike',  '%' . $daftar_petugas[$i] .'%');
                 }
             })->get();
        }
        
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'userspetugas'=>$data,
                                'message'=>'Fetch data users Petugas berhasil diperoleh'
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
        // $this->hasPermissionTo('USERS PETUGAS_STORE');
        
        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|string|email|unique:users',
            'username'=>'required|string|unique:users',
            'password'=>'required',
            'nomor_hp'=>'required',                
            'PmKecamatanID'=>'required',            
            'Nm_Kecamatan'=>'required',            
            'PmDesaID'=>'required',            
            'Nm_Desa'=>'required', 
        ]);
        $now = \Carbon\Carbon::now()->toDateTimeString();        
        $user=User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'username'=> $request->input('username'),
            'password'=>Hash::make($request->input('password')),
            'nomor_hp'=>$request->input('nomor_hp'),
            'PmKecamatanID'=>$request->input('PmKecamatanID'),
            'Nm_Kecamatan'=>$request->input('Nm_Kecamatan'),
            'PmDesaID'=>$request->input('PmDesaID'),
            'Nm_Desa'=>$request->input('Nm_Desa'),            
            'payload'=>'{}',            
            'foto'=>'storage/images/users/no_photo.png',            
            'created_at'=>$now, 
            'updated_at'=>$now
        ]);            
        $role='petugas';   
        $user->assignRole($role);               
        
        \App\Models\Setting\ActivityLog::log($request,[
                                        'object' => $this->guard()->user(), 
                                        'user_id' => $this->guard()->user()->id, 
                                        'message' => 'Menambah user Petugas('.$user->username.') berhasil'
                                    ]);

        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'store',
                                    'user'=>$user,                                    
                                    'message'=>'Data user Petugas berhasil disimpan.'
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

        $user = User::find($id);
        if ($request->has('dialog'))
        {
            $this->validate($request, [
                                        'username'=>['required',new IgnoreIfDataIsEqualValidation('users',$user->username)],           
                                        'name'=>'required',            
                                        'email'=>'required|string|email|unique:users,email,'.$id,
                                        'nomor_hp'=>$request->input('nomor_hp'),
                                        'PmKecamatanID'=>$request->input('PmKecamatanID'),
                                        'Nm_Kecamatan'=>$request->input('Nm_Kecamatan'),
                                        'PmDesaID'=>$request->input('PmDesaID'),
                                        'Nm_Desa'=>$request->input('Nm_Desa'),                
                                    ]); 
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->nomor_hp = $request->input('username');
            $user->PmKecamatanID = $request->input('username');
            $user->Nm_Kecamatan = $request->input('username');
            $user->Nm_Desa = $request->input('username');
            
            if (!empty(trim($request->input('password')))) {
                $user->password = Hash::make($request->input('password'));
            }    
            $user->updated_at = \Carbon\Carbon::now()->toDateTimeString();
            $user->save();

            \App\Models\Setting\ActivityLog::log($request,[
                                                        'object' => $this->guard()->user(), 
                                                        'user_id' => $this->guard()->user()->id, 
                                                        'message' => 'Mengubah data user Petugas('.$user->username.') berhasil'
                                                    ]);

            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'user'=>$user,      
                                    'message'=>'Data user Petugas '.$user->username.' berhasil diubah.'
                                ],200); 
        }
        else
        {   
            $user->payload=$request->input('payload');

            $user->save();

            \App\Models\Setting\ActivityLog::log($request,[
                                                        'object' => $this->guard()->user(), 
                                                        'user_id' => $this->guard()->user()->id, 
                                                        'message' => 'Mengubah data hak akses user Petugas ('.$user->username.') dengan Petugas = '.$user->payload.' berhasil'
                                                    ]);
            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'user'=>$user,                                    
                                    'message' => 'Mengubah data hak akses user Petugas ('.$user->username.') dengan Petugas = '.$user->payload.' berhasil'
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
        // $this->hasPermissionTo('USERS PETUGAS_DESTROY');

        $user = User::where('isdeleted','t')
                    ->find($id); 

        if ($user instanceof User)
        {
            $username=$user->username;
            $user->delete();

            \App\Models\Setting\ActivityLog::log($request,[
                                                                'object' => $this->guard()->user(), 
                                                                'user_id' => $this->guard()->user()->id, 
                                                                'message' => 'Menghapus user Petugas('.$username.') berhasil'
                                                            ]);
        }
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'destroy',                
                                    'message'=>"User Petugas ($username) berhasil dihapus"
                                ],200);         
                  
    }
}