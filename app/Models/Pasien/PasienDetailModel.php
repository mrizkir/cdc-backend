<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class PasienDetailModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
            
        'founded_alamat',
        'founded_PmKecamatanID',
        'founded_Nm_Kecamatan',
        'founded_PmDesaID',
        'founded_Nm_Desa',
        'founded_lat',
        'founded_lng',

        'FasilitasKarantinaID',
        'karantina_alamat',            
        'karantina_PmKecamatanID',
        'karantina_Nm_Kecamatan',
        'karantina_PmDesaID',
        'karantina_Nm_Desa',
        
        'karantina_mulai',
        'karantina_selesai',            
        'karantina_time',

        'transmisi_penularan',
        'ket_transmisi',
        
        'jenis_test',
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';
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
