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
        \DB::statement('TRUNCATE "tmStatusPasien" RESTART IDENTITY CASCADE');
                
        \DB::table('tmStatusPasien')->insert([
            [
                'id_status'=>0,
                'nama_status'=>'MENINGGAL',
            ], 
            [
                'id_status'=>1,
                'name'=>'POSITIF'
            ],
            [ 
                'id_status'=>2,
                'nama_status'=>'ORANG TANPA GEJALA',
            ],
            [
                'id_status'=>3,
                'nama_status'=>'PASIEN DALAM PEMANTAUAN',
            ],
            [
                'id_status'=>4,
                'nama_status'=>'ORANG DALAM PEMANTAUAN',
            ],
            [
                'id_status'=>5,
                'nama_status'=>'SEMBUH',
            ],
            [
                'id_status'=>6,
                'nama_status'=>'NEGATIF'
            ]        
        ]);
        
    }
}
