<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class LokasiPasienModel extends Model
{
    /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'pasien_location';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'lat', 
        'lng', 
        'Descr', 
    ];
    /**
     * enable auto_increment.
     *
     * @var string
     */
    public $incrementing = true;
    /**
     * activated timestamps.
     *
     * @var string
     */
    public $timestamps = true;
}