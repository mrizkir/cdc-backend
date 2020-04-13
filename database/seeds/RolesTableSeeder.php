<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('TRUNCATE roles RESTART IDENTITY CASCADE');
                
        \DB::table('roles')->insert([
            [
                'name'=>'superadmin',
                'guard_name'=>'api',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ], 
            [
                'name'=>'gugustugas',
                'guard_name'=>'api',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],    
            [
                'name'=>'petugas',
                'guard_name'=>'api',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],                    
            [
                'name'=>'pasien',
                'guard_name'=>'api',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],                    
        ]);
    }
}
