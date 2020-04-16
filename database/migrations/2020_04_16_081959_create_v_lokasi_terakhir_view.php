<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVLokasiTerakhirView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE VIEW v_lokasi_terakhir AS
            SELECT 
                users.id,
                username,
                users.status_pasien,
                C.nama_status,
                B.lat,
                B.lng,
                B.updated_at
            FROM 
                users 
            JOIN (SELECT user_id,MAX(id) AS lokasi_id FROM pasien_location GROUP BY user_id) AS A ON (A.user_id=users.id)
            JOIN pasien_location B ON (B."id"=A.lokasi_id)
            JOIN "tmStatusPasien" C ON (users.status_pasien=C.id_status)
            ORDER BY 
                users.created_at ASC
        ');				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW v_lokasi_terakhir');
    }
}
