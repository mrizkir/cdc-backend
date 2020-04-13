<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('TRUNCATE permissions RESTART IDENTITY CASCADE');
        
        $modules = [
            'DASHBOARD',            
            'PERMISSIONS',
            'ROLES',
            'USERS',
            'USERS GUGUSTUGAS',
            'USERS PETUGAS',
            'USERS PASIEN',
        ];
        $records=[];
        foreach($modules as $v)
        {
            $records=array(
                ['name'=>"{$v}_BROWSE",'guard_name'=>'api','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
                ['name'=>"{$v}_SHOW",'guard_name'=>'api','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
                ['name'=>"{$v}_STORE",'guard_name'=>'api','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
                ['name'=>"{$v}_UPDATE",'guard_name'=>'api','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
                ['name'=>"{$v}_DESTROY",'guard_name'=>'api','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]                
            );            
            \DB::table('permissions')->insert($records);
        }        
        \DB::table('permissions')->insert([
            'name'=>"USER_STOREPERMISSIONS",
            'guard_name'=>'api',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);                
        \DB::table('permissions')->insert([
            'name'=>"USER_REVOKEPERMISSIONS",
            'guard_name'=>'api',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();                
    }
}
