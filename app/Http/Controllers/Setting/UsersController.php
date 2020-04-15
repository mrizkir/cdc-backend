<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Rules\IgnoreIfDataIsEqualValidation;
use App\Models\User;
use App\Helpers\Helper;
use Spatie\Permission\Models\Role;

class UsersController extends Controller {         
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {           
        $this->hasPermissionTo('USERS_BROWSE');
        $data = User::role('superadmin')->get();
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'users'=>$data,
                                'message'=>'Fetch data users berhasil diperoleh'
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
        $this->hasPermissionTo('USERS_STORE');
        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|string|email|unique:users',
            'username'=>'required|string|unique:users',
            'password'=>'required',
        ]);
        $now = \Carbon\Carbon::now()->toDateTimeString();        
        $user=User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'username'=> $request->input('username'),
            'password'=>Hash::make($request->input('password')),
            'email_verified_at'=>\Carbon\Carbon::now(),
            'theme'=>$request->input('theme'),
            'payload'=>'{}',
            'created_at'=>$now, 
            'updated_at'=>$now
        ]);            
        $role='superadmin';   
        $user->assignRole($role);               
        
        \App\Models\Setting\ActivityLog::log($request,[
                                        'object' => $this->guard()->user(), 
                                        'user_id' => $this->guard()->user()->id, 
                                        'message' => 'Menambah user ('.$user->username.') berhasil'
                                    ]);

        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'store',
                                    'user'=>$user,                                    
                                    'message'=>'Data user berhasil disimpan.'
                                ],200); 

    }    
    /**
     * Store user permissions resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeuserpermissions(Request $request)
    {      
        $this->hasPermissionTo('USER_STOREPERMISSIONS');

        $post = $request->all();
        $permissions = isset($post['chkpermission'])?$post['chkpermission']:[];
        $user_id = $post['user_id'];

        foreach($permissions as $k=>$v)
        {
            $records[]=$v['name'];
        }        
        
        $user = User::find($user_id);
        $user->givePermissionTo($records);

        \App\Models\Setting\ActivityLog::log($request,[
                                                        'object' => $this->guard()->user(), 
                                                        'user_id' => $this->guard()->user()->id, 
                                                        'message' => 'Mensetting permission user ('.$user->username.') berhasil'
                                                    ]);
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'store',
                                    'message'=>'Permission user '.$user->username.' berhasil disimpan.'
                                ],200); 
    }
    /**
     * Store user permissions resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function revokeuserpermissions(Request $request)
    {      
        $this->hasPermissionTo('USER_REVOKEPERMISSIONS');

        $post = $request->all();
        $name = $post['name'];
        $user_id = $post['user_id'];
      
        
        $user = User::find($user_id);
        $user->revokePermissionTo($name);

        \App\Models\Setting\ActivityLog::log($request,[
                                        'object' => $this->guard()->user(), 
                                        'user_id' => $this->guard()->user()->id, 
                                        'message' => 'Menghilangkan permission('.$name.') user ('.$user->username.') berhasil'
                                    ]);
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'destroy',
                                    'message'=>'Role user '.$user->username.' berhasil di revoke.'
                                ],200); 
    }
    /**
     * Display the specified resource.     
     * @return \Illuminate\Http\Response
     */
    public function profil()
    {
                
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
        $this->hasPermissionTo('USERS_UPDATE');

        $user = User::find($id);
        $this->validate($request, [
            'username'=>['required',new IgnoreIfDataIsEqualValidation('users',$user->username)],           
            'name'=>'required',            
            'email'=>'required|string|email|unique:users,email,'.$id              
        ]); 
        
        
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->theme = $request->input('theme');
        $user->payload = '{}';
        if (!empty(trim($request->input('password')))) {
            $user->password = Hash::make($request->input('password'));
        }    
        $user->updated_at = \Carbon\Carbon::now()->toDateTimeString();
        $user->save();

        \App\Models\Setting\ActivityLog::log($request,[
                                                        'object' => $this->guard()->user(), 
                                                        'user_id' => $this->guard()->user()->id, 
                                                        'message' => 'Mengubah data user ('.$user->username.') berhasil'
                                                    ]);

        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'user'=>$user,                                    
                                    'message'=>'Data user '.$user->username.' berhasil diubah.'
                                ],200); 
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateprofil(Request $request)
    {
        $user = \Auth::user();
        $id = $user->id;

        $this->validate($request, [        
            'email'=>'required|string|email|unique:users,email,'.$id,                          
        ]);
        
        $user->email = $request->input('email');
        if (!empty(trim($request->input('password1')))) {
            $user->password = \Hash::make($request->input('password1'));
        }    
        $user->updated_at = \Carbon\Carbon::now()->toDateTimeString();
        $user->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('users.profil',['id'=>$id]))->with('success',"Data profil telah berhasil diubah.");
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
        $this->hasPermissionTo('USERS_DESTROY');

        $user = User::where('isdeleted','t')
                    ->find($id); 

        if ($user instanceof User)
        {
            $username=$user->username;
            $user->delete();

            \App\Models\Setting\ActivityLog::log($request,[
                                                                'object' => $this->guard()->user(), 
                                                                'user_id' => $this->guard()->user()->id, 
                                                                'message' => 'Menghapus user ('.$username.') berhasil'
                                                            ]);
        }
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'destroy',                
                                    'message'=>"User ($username) berhasil dihapus"
                                ],200);         
                  
    }

    public function uploadfoto (Request $request,$id)
    {
        $user = User::find($id); 
        
        if ($user == null)
        {
            return Response()->json([
                                    'status'=>0,
                                    'pid'=>'store',                
                                    'message'=>"Data Pasien tidak ditemukan."
                                ],422);         
        }
        else
        {
            $this->validate($request, [        
                'foto'=>'required',                          
            ]);
            $username=$user->username;
            $foto = $request->file('foto');
            $mime_type=$foto->getMimeType();
            if ($mime_type=='image/png' || $mime_type=='image/jpeg')
            {
                $folder=Helper::public_path('images/users/');
                $file_name=uniqid('img').".".$foto->getClientOriginalExtension();
                $foto->move($folder,$file_name);
                $user->foto="storage/images/users/$file_name";
                $user->save();

                return Response()->json([
                                            'status'=>0,
                                            'pid'=>'store',
                                            'user'=>$user,                
                                            'message'=>"Foto User ($username)  berhasil diupload"
                                        ],200);    
            }
            else
            {
                return Response()->json([
                                        'status'=>1,
                                        'pid'=>'store',
                                        'message'=>"Extensi file yang diupload bukan jpg atau png."
                                    ],422); 
                

            }
        }
    }
}