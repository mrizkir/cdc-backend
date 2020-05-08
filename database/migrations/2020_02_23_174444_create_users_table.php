<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::defaultStringLength(191);
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('username')->unique();
            $table->string('password');            
            $table->string('name');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jk',1)->nullable();
            $table->string('gol_darah',2)->nullable();
            $table->string('nomor_hp',19)->nullable();
            $table->string('alamat')->nullable();
            $table->string('PmKecamatanID',19)->nullable();
            $table->string('Nm_Kecamatan')->nullable();
            $table->string('PmDesaID',19)->nullable();
            $table->string('Nm_Desa')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('foto')->default('storage/images/users/no_photo.png');
            $table->tinyInteger('status_pasien')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('isdeleted')->default(1);
            $table->boolean('locked')->default(0);                          
            $table->string('payload')->nullable();

            $table->timestamps();

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
        Schema::dropIfExists('users');
    }
}
