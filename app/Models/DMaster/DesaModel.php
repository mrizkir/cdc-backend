<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class DesaModel extends Model {    
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPmDesa';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PmDesaID', 
        'PmKecamatanID',
        'Kd_Desa',
        'Nm_Desa',
        'lat',
        'lng',
        'Descr',        
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'PmDesaID';
    /**
     * enable auto_increment.
     *
     * @var string
     */
    public $incrementing = false;
    /**
     * activated timestamps.
     *
     * @var string
     */
    public $timestamps = false;

}
