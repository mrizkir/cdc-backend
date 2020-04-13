<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class TAModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmTA';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'TAID', 'TACd', 'TANm', 'Descr'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'TAID';
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

    /**
     * make the model use another name than the default
     *
     * @var string
     */
    protected static $logName = 'TAController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['TAID', 'TANm'];
}
