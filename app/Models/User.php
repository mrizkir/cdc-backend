<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable,HasRoles;
    
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 
        'password', 
        'name', 
        'tempat_lahir', 
        'tanggal_lahir', 
        'jk', 
        'gol_darah', 
        'nomor_hp', 
        'alamat', 
        'PmKecamatanID', 
        'Nm_Kecamatan', 
        'PmDesaID', 
        'Nm_Desa', 
        'email', 
        'foto',         
        'status_pasien', 
        'active', 
        'isdeleted', 
        'locked', 
        'payload',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
    
    /**
     * activated timestamps.
     *
     * @var string
     */
    public $timestamps = true;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }   
}
