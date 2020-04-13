<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller {      
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $this->hasPermissionTo('PERMISSIONS_BROWSE');
        $user=$this->guard()->user();
        if ($user->hasRole('superadmin'))
        {
            $data = Permission::all();
        }
        else if ($user->hasRole('bapelitbang'))
        {
            $data = Role::findByName('bapelitbang')->permissions;
        }
        else if ($user->hasRole('opd'))
        {
            $data = Role::findByName('opd')->permissions;
        }
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'permissions'=>$data,
                                'message'=>'Fetch data permissions berhasil diperoleh'
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
        $this->hasPermissionTo('PERMISSIONS_STORE');

        $this->validate($request, [
            'name'=>'required',
        ],[
            'name.required'=>'Nama permission mohon untuk di isi',
        ]
        );
        $aksi = $request->input('aksi');
        $permission = new Permission;        
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $nama = strtoupper($request->input('name'));   
        
        $permission->insert([
            ['name'=>"{$nama}_BROWSE",'guard_name'=>'api','created_at'=>$now, 'updated_at'=>$now],
            ['name'=>"{$nama}_SHOW",'guard_name'=>'api','created_at'=>$now, 'updated_at'=>$now],
            ['name'=>"{$nama}_STORE",'guard_name'=>'api','created_at'=>$now, 'updated_at'=>$now],
            ['name'=>"{$nama}_UPDATE",'guard_name'=>'api','created_at'=>$now, 'updated_at'=>$now],
            ['name'=>"{$nama}_DESTROY",'guard_name'=>'api','created_at'=>$now, 'updated_at'=>$now],
        ]);
        
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'store',
                                    'permission'=>$permission,                                    
                                    'message'=>'Data permission berhasil disimpan.'
                                ],200); 
    
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $this->hasPermissionTo('PERMISSIONS_DESTROY');

        $permissions = Permission::find($id);
        $permission = $permissions->name;
        $result=$permissions->delete();        

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        
        \App\Models\Setting\ActivityLog::log($request,[
                                                    'object' => $this->guard()->user(), 
                                                    'user_id' => $this->guard()->user()->id, 
                                                    'message' => 'Menghapus permission ('.$permission.') berhasil'
                                                ]);                                                                 
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'destroy',                
                                    'message'=>"Permission ($permission) berhasil dihapus"
                                ],200); 
    }
}