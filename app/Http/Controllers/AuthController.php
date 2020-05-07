<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\DMaster\StatusPasienModel;
use App\Models\DMaster\KecamatanModel;
use App\Models\DMaster\DesaModel;


class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (! $token = $this->guard()->attempt($credentials)) {
            return response()->json([
                                    'page' => 'login',
                                    'error' => 'Unauthorized',                                    
                                ], 401);
        }
        //log user loggin
        \App\Models\Setting\ActivityLog::log($request,[
                                                        'object' => $this->guard()->user(), 
                                                        'user_id' => $this->guard()->user()->id, 
                                                        'message' => 'user '.$credentials['username'].' berhasil login'
                                                    ]);
        return $this->respondWithToken($token);
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = $this->guard()->user()->toArray();
        $status_pasien=StatusPasienModel::find($user['status_pasien']);
        if (!is_null($status_pasien))
        {
            $user['nama_status']=$status_pasien->nama_status;
        }
        $kecamatan=KecamatanModel::find($user['PmKecamatanID']);
        if(!is_null($kecamatan))
        {
            $user['kecamatan_lat']=$kecamatan->lat;
            $user['kecamatan_lng']=$kecamatan->lat;   
        }        
        $desa=DesaModel::find($user['PmDesaID']);
        if(!is_null($desa))
        {
            $user['desa_lat']=$desa->lat;
            $user['desa_lng']=$desa->lat;   
        }             
           
        $user['role']=$this->guard()->user()->getRoleNames()->toArray();
        $user['issuperadmin']=$this->guard()->user()->hasRole('superadmin');
        $user['permissions']=$this->guard()->user()->permissions->pluck('id','name')->toArray();
        return response()->json($user);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        //log user logout
        \App\Models\Setting\ActivityLog::log($request,[
            'object' => $this->guard()->user(), 
            'user_id' => $this->guard()->user()->id, 
            'message' => 'user '.$this->guard()->user()->username.' berhasil logout'
        ],1);

        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out'],200);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }
}
