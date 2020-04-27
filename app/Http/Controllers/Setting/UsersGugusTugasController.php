<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Rules\IgnoreIfDataIsEqualValidation;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersGugusTugasController extends Controller {         
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {           
        // $this->hasPermissionTo('USERS GUGUSTUGAS_BROWSE');

        $user=$this->guard()->user();
        if ($user->hasRole(['superadmin','gugustugas']))
        {
            $data = User::role('gugustugas')->get();
        }       
        else if ($user->hasRole('gugustugas'))
        {
            $daftar_gugustugas=json_decode($user->payload,true);
            $data = User::role('gugustugas')->where(function ($query) use ($daftar_gugustugas){
                for ($i = 0; $i < count($daftar_gugustugas); $i++){
                    $query->orwhere('payload', 'ilike',  '%' . $daftar_gugustugas[$i] .'%');
                 }
             })->get();
        }
        
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'usersgugustugas'=>$data,
                                'message'=>'Fetch data users Gugus Tugas berhasil diperoleh'
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
        // $this->hasPermissionTo('USERS GUGUSTUGAS_STORE');
        
        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|string|email|unique:users',
            'username'=>'required|string|unique:users',
            'password'=>'required',
            'nomor_hp'=>'required',                           
        ]);
        $now = \Carbon\Carbon::now()->toDateTimeString();        
        $user=User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'username'=> $request->input('username'),
            'password'=>Hash::make($request->input('password')),
            'nomor_hp'=>$request->input('nomor_hp'),                  
            'payload'=>'{}',            
            'foto'=>'storage/images/users/no_photo.png',            
            'created_at'=>$now, 
            'updated_at'=>$now
        ]);            
        $role='gugustugas';   
        $user->assignRole($role);               
        
        \App\Models\Setting\ActivityLog::log($request,[
                                        'object' => $this->guard()->user(), 
                                        'user_id' => $this->guard()->user()->id, 
                                        'message' => 'Menambah user Gugus Tugas('.$user->username.') berhasil'
                                    ]);

        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'store',
                                    'user'=>$user,                                    
                                    'message'=>'Data user Gugus Tugas berhasil disimpan.'
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
        // $this->hasPermissionTo('USERS GUGUSTUGAS_UPDATE');

        $user = User::find($id);
        
        if ($user == null)
        {
            return Response()->json([
                                    'status'=>0,
                                    'pid'=>'update',                
                                    'message'=>"Data Gugus Tugas tidak ditemukan"
                                ],422);         
        }
        else
        {
            $this->validate($request, [
                                        'username'=>['required',new IgnoreIfDataIsEqualValidation('users',$user->username)],           
                                        'name'=>'required',            
                                        'email'=>'required|string|email|unique:users,email,'.$id,
                                        'nomor_hp'=>'required',                                                        
                                    ]); 
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->nomor_hp = $request->input('nomor_hp');            
            
            if (!empty(trim($request->input('password')))) {
                $user->password = Hash::make($request->input('password'));
            }    
            $user->updated_at = \Carbon\Carbon::now()->toDateTimeString();
            $user->save();

            \App\Models\Setting\ActivityLog::log($request,[
                                                        'object' => $this->guard()->user(), 
                                                        'user_id' => $this->guard()->user()->id, 
                                                        'message' => 'Mengubah data user Gugus Tugas('.$user->username.') berhasil'
                                                    ]);

            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'user'=>$user,      
                                    'message'=>'Data user Gugus Tugas '.$user->username.' berhasil diubah.'
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
        // $this->hasPermissionTo('USERS GUGUSTUGAS_DESTROY');

        $user = User::where('isdeleted','t')
                    ->find($id); 

            if ($user == null)
            {
                return Response()->json([
                                        'status'=>0,
                                        'pid'=>'destroy',                
                                        'message'=>"Data Gugus Tugas tidak ditemukan."
                                    ],422);         
            }
            else
            {
            $username=$user->username;
            $user->delete();

            \App\Models\Setting\ActivityLog::log($request,[
                                                                'object' => $this->guard()->user(), 
                                                                'user_id' => $this->guard()->user()->id, 
                                                                'message' => 'Menghapus user Gugus Tugas('.$username.') berhasil'
                                                            ]);
        }
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'destroy',                
                                    'message'=>"User Gugus Tugas ($username) berhasil dihapus"
                                ],200);         
                  
    }
}