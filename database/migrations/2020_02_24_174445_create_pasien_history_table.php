<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasienHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::defaultStringLength(191);
        Schema::create('pasien_history', function (Blueprint $table) {
            $table->increments('id');            
            $table->unsignedInteger('user_id');
            $table->tinyInteger('id_status');            
            $table->string('nama_status');            
            $table->string('Descr');
            $table->timestamps();

            $table->index('user_id');

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pasien_history');
    }
}
