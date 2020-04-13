<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class Controller extends BaseController
{    
    /**
     * @return object auth api
     */
    public function guard() 
    {
        return Auth::guard('api');
    }
    /**
     * @return object auth api
     */
    public function hasPermissionTo($permission) 
    {
        $user = Auth::guard('api')->user();
        if ($this->guard()->guest())
        {
            return true;
        }
        elseif ($user->hasPermissionTo($permission) || $user->hasRole('superadmin'))
        {
            return true;
        }
        else
        {
            abort(403,'Forbidden: You have not a privilege to execute this process '.$permission);
        }        
    }
    /**
     * Display the specified user permissions.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userpermissions($id)
    {
        $user = User::find($id);
        $permissions=is_null($user)?[]:$user->permissions;     
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'fetchdata',
                                    'permissions'=>$permissions,                                    
                                    'message'=>'Fetch permission role '.$user->username.' berhasil diperoleh.'
                                ],200); 
    }
    /**
     * Display the specified opd by userid.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function useropd($id)
    {
        $user = User::find($id);
        $opd=is_null($user)?[]:$user->opd->orderBy('OrgID','ASC');     
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'fetchdata',
                                    'opd'=>$opd,                                    
                                    'message'=>'Fetch opd berdasarkan '.$user->username.' berhasil diperoleh.'
                                ],200); 
    }
}
