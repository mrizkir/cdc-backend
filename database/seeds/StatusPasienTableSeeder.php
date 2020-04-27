<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class StatusPasienTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       
        \DB::statement('TRUNCATE "pasien_status" RESTART IDENTITY CASCADE');
                
        \DB::table('pasien_status')->insert([
            [
                'id_status'=>0,
                'nama_status'=>'POSITIF MENINGGAL',
            ], 
            [
                'id_status'=>1,
                'name'=>'POSITIF AKTIF'
            ],
            [ 
                'id_status'=>2,
                'nama_status'=>'POSITIF SEMBUH',
            ],
            [
                'id_status'=>3,
                'nama_status'=>'PDP PROSES',
            ],
            [
                'id_status'=>4,
                'nama_status'=>'PDP SELESAI',
            ],
            [
                'id_status'=>5,
                'nama_status'=>'ODP PROSES',
            ],
            [
                'id_status'=>6,
                'nama_status'=>'ODP SELESAI'
            ]        
        ]);
        
    }
}
