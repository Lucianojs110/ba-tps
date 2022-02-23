<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCala extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('tipo_grano', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);    
        });
        
        Schema::create('calas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proveedor_id');
            $table->dateTime('entrada');
            $table->unsignedBigInteger('tipo_grano_id');
            $table->decimal('cantidad', 10, 2)->nullable();
            $table->string('condicion', 20)->nullable();
            $table->string('humedad', 20)->nullable();
            $table->string('num_carta_porte', 30)->nullable();
            $table->timestamps();

            $table->foreign('tipo_grano_id')
            ->references('id')
            ->on('tipo_grano')
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
        Schema::dropIfExists('cala');
    }
}
