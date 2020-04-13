<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\IgnoreIfDataIsEqualValidation;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller {    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $this->hasPermissionTo('ROLES_DESTROY');
        $data = Role::all();
        return Response()->json([
                                'status'=>1,
                                'pid'=>'fetchdata',
                                'roles'=>$data,
                                'message'=>'Fetch data stores berhasil diperoleh'
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
        $this->hasPermissionTo('ROLES_STORE');
        $this->validate($request, [
            'name'=>'required|unique:roles',
        ],[
            'name.required'=>'Nama role mohon untuk di isi',
            'name.unique'=>'Nama role telah ada, mohon untuk diganti dengan yang lain'
        ]
        );
        
        $role = new Role;
        $role->name = $request->input('name');
        $role->save();
       
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'store',
                                    'role'=>$role,                                    
                                    'message'=>'Data role berhasil disimpan.'
                                ],200); 

    }
    /**
     * Store a roles resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storerolepermissions(Request $request)
    {      
        $this->hasPermissionTo('ROLES_STORE');

        $post = $request->all();
        $permissions = isset($post['chkpermission'])?$post['chkpermission']:[];
        $role_id = $post['role_id'];

        foreach($permissions as $k=>$v)
        {
            $records[]=$v['name'];
        }        
        
        $role = Role::find($role_id);
        $role->syncPermissions($records);
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'store',
                                    'message'=>'Permission role '.$role->name.' berhasil disimpan.'
                                ],200); 
    }
    /**
     * Display the specified role permissions.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rolepermissions($id)
    {
        $this->hasPermissionTo('ROLES_SHOW');
        $role=Role::findByID($id);
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'fetchdata',
                                    'permissions'=>$role->permissions,                                    
                                    'message'=>'Fetch permission role '.$role->name.' berhasil diperoleh.'
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
        $this->hasPermissionTo('ROLES_UPDATE');

        $role = Role::find($id);

        $this->validate($request, [
            'name'=>['required',new IgnoreIfDataIsEqualValidation('roles',$role->name)],           
        ],[
            'name.required'=>'Nama role mohon untuk di isi',
        ]);        
       
        $role->name = $request->input('name');
        $role->save();

        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'update',
                                    'role'=>$role,                                    
                                    'message'=>'Data role '.$role->name.' berhasil diubah.'
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
        $this->hasPermissionTo('ROLES_DESTROY');
        
        $role = Role::find($id);
        $name = $role->name;
        $result=$role->delete();                                                                         
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'destroy',                
                                    'message'=>"Role ($name) berhasil dihapus"
                                ],200); 
    }
}