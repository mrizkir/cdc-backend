<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class MenusModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'permission_id', 'title', 'icon', 'link', 'haveSubmenu', 'parent'
    ];   
    /**
     * activated timestamps.
     *
     * @var string
     */
    public $timestamps = true;
    
}
