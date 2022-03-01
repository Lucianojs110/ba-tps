<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngreso extends Migration
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
        
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_proveedor');
            $table->date('fecha_entrada');
            $table->time('hora_entrada');
            $table->unsignedBigInteger('id_tipo_grano');
            $table->decimal('cantidad', 10, 2)->nullable();
            $table->string('condicion', 20)->nullable();
            $table->string('humedad', 20)->nullable();
            $table->string('num_carta_porte', 30)->nullable();
            $table->timestamps();

            $table->foreign('id_tipo_grano')
            ->references('id')
            ->on('tipo_grano')
            ->onDelete('cascade');

            $table->foreign('id_proveedor')
            ->references('id')
            ->on('proveedores')
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
        Schema::dropIfExists('ingreso');
    }
}
