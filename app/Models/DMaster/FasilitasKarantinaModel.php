<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class FasilitasKarantinaModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmFasilitasKarantina';
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'FasilitasKarantinaID';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'FasilitasKarantinaID', 
        'Nm_Fasilitas', 
        'alamat',
        'PmKecamatanID', 
        'Nm_Kecamatan', 
        'PmDesaID', 
        'Nm_Desa',              
    ];
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
    public $timestamps = true;
}
