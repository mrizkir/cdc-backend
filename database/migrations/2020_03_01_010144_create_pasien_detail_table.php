<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasienDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::defaultStringLength(191);
        Schema::create('pasien_detail', function (Blueprint $table) {
            $table->unsignedInteger('user_id',1);
            
            $table->string('founded_alamat');
            $table->string('founded_PmKecamatanID',19);
            $table->string('founded_Nm_Kecamatan');
            $table->string('founded_PmDesaID',19);
            $table->string('founded_Nm_Desa');
            $table->string('founded_lat',25);
            $table->string('founded_lng',25);

            $table->string('FasilitasKarantinaID',19)->nullable();
            $table->string('karantina_alamat');            
            $table->string('karantina_PmKecamatanID',19);
            $table->string('karantina_Nm_Kecamatan');
            $table->string('karantina_PmDesaID',19);
            $table->string('karantina_Nm_Desa');
            
            $table->date('karantina_mulai');
            $table->date('karantina_selesai');            
            $table->tinyInteger('karantina_time');

            $table->string('transmisi_penularan',10);
            $table->string('ket_transmisi');
            
            $table->string('jenis_test',10);            

            $table->timestamps();            
            $table->index('FasilitasKarantinaID');
            $table->index('karantina_PmKecamatanID');
            $table->index('karantina_PmDesaID');            
            

            $table->foreign('user_id')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
            

            $table->foreign('FasilitasKarantinaID')
                            ->references('FasilitasKarantinaID')
                            ->on('tmFasilitasKarantina')
                            ->onDelete('cascade')
                            ->onUpdate('cascade');

            $table->foreign('karantina_PmKecamatanID')
                            ->references('PmKecamatanID')
                            ->on('tmPmKecamatan')
                            ->onDelete('cascade')
                            ->onUpdate('cascade');

            $table->foreign('karantina_PmDesaID')
                            ->references('PmDesaID')
                            ->on('tmPmDesa')
                            ->onDelete('cascade')
                            ->onUpdate('cascade');


            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pasien_detail');
    }
}
