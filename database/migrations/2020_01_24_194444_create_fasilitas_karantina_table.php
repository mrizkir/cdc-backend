<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFasilitasKarantinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::defaultStringLength(191);
        Schema::create('tmFasilitasKarantina', function (Blueprint $table) {
            $table->string('FasilitasKarantinaID',19);            
            $table->string('Nm_Fasilitas');
            $table->string('alamat')->nullable();
            $table->string('PmKecamatanID',19)->nullable();
            $table->string('Nm_Kecamatan')->nullable();
            $table->string('PmDesaID',19)->nullable();
            $table->string('Nm_Desa')->nullable();
            
            $table->timestamps();

            $table->primary('FasilitasKarantinaID');
            $table->index('PmKecamatanID');
            $table->index('PmDesaID');
            
            $table->foreign('PmKecamatanID')
                            ->references('PmKecamatanID')
                            ->on('tmPmKecamatan')
                            ->onDelete('cascade')
                            ->onUpdate('cascade');

            $table->foreign('PmDesaID')
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
        Schema::dropIfExists('tmFasilitasKarantina');
    }
}
