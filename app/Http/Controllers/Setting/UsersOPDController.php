<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Rules\IgnoreIfDataIsEqualValidation;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersOPDController extends Controller {         
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {           
        $this->hasPermissionTo('USERS OPD_BROWSE');
        $user=$this->guard()->user();
        if ($user->hasRole(['superadmin','bapelitbang']))
        {
            $data = User::role('opd')->get();
        }       
        else if ($user->hasRole('opd'))
        {
            $daftar_opd=json_decode($user->payload,true);
            $data = User::role('opd')->where(function ($query) use ($daftar_opd){
                for ($i = 0; $i < count($daftar_opd); $i++){
                    $query->orwhere('payload', 'ilike',  '%' . $daftar_opd[$i] .'%');
                 }
             })->get();
        }
        
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'usersopd'=>$data,
                                'message'=>'Fetch data users OPD berhasil diperoleh'
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
        $this->hasPermissionTo('USERS OPD_STORE');
        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|string|email|unique:users',
            'username'=>'required|string|unique:users',
            'password'=>'required',
            'payload'=>'required',
        ]);
        $now = \Carbon\Carbon::now()->toDateTimeString();        
        $user=User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'username'=> $request->input('username'),
            'password'=>Hash::make($request->input('password')),
            'email_verified_at'=>\Carbon\Carbon::now(),
            'payload'=>$request->input('payload'),
            'theme'=>$request->input('theme'),
            'created_at'=>$now, 
            'updated_at'=>$now
        ]);            
        $role='opd';   
        $user->assignRole($role);               
        
        \App\Models\Setting\ActivityLog::log($request,[
                                        'object' => $this->guard()->user(), 
                                        'user_id' => $this->guard()->user()->id, 
                                        'message' => 'Menambah user OPD('.$user->username.') berhasil'
                                    ]);

        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'store',
                                    'user'=>$user,                                    
                                    'message'=>'Data user OPD berhasil disimpan.'
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
        $this->hasPermissionTo('USERS OPD_UPDATE');

        $user = User::find($id);
        if ($request->has('dialog'))
        {
            $this->validate($request, [
                                        'username'=>['required',new IgnoreIfDataIsEqualValidation('users',$user->username)],           
                                        'name'=>'required',            
                                        'email'=>'required|string|email|unique:users,email,'.$id              
                                    ]); 
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->username = $request->input('username');
            $user->theme = $request->input('theme');
            if (!empty(trim($request->input('password')))) {
                $user->password = Hash::make($request->input('password'));
            }    
            $user->updated_at = \Carbon\Carbon::now()->toDateTimeString();
            $user->save();

            \App\Models\Setting\ActivityLog::log($request,[
                                                        'object' => $this->guard()->user(), 
                                                        'user_id' => $this->guard()->user()->id, 
                                                        'message' => 'Mengubah data user OPD('.$user->username.') berhasil'
                                                    ]);

            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'user'=>$user,      
                                    'message'=>'Data user OPD '.$user->username.' berhasil diubah.'
                                ],200); 
        }
        else
        {   
            $user->payload=$request->input('payload');

            $user->save();

            \App\Models\Setting\ActivityLog::log($request,[
                                                        'object' => $this->guard()->user(), 
                                                        'user_id' => $this->guard()->user()->id, 
                                                        'message' => 'Mengubah data hak akses user OPD ('.$user->username.') dengan OPD = '.$user->payload.' berhasil'
                                                    ]);
            return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'user'=>$user,                                    
                                    'message' => 'Mengubah data hak akses user OPD ('.$user->username.') dengan OPD = '.$user->payload.' berhasil'
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
        $this->hasPermissionTo('USERS OPD_DESTROY');

        $user = User::where('isdeleted','t')
                    ->find($id); 

        if ($user instanceof User)
        {
            $username=$user->username;
            $user->delete();

            \App\Models\Setting\ActivityLog::log($request,[
                                                                'object' => $this->guard()->user(), 
                                                                'user_id' => $this->guard()->user()->id, 
                                                                'message' => 'Menghapus user OPD('.$username.') berhasil'
                                                            ]);
        }
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'destroy',                
                                    'message'=>"User OPD ($username) berhasil dihapus"
                                ],200);         
                  
    }
}