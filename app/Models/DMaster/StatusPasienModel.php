<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class StatusPasienModel extends Model {    
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'pasien_status';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_status', 
        'nama_status',       
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'id_status';
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
