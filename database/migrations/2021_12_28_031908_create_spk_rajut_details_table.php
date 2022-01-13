<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpkRajutDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spk_rajut_details', function (Blueprint $table) {
            $table->integer('mesin_id')->unsigned();
            $table->integer('material_id')->unsigned();
            $table->integer('material_raw_id')->unsigned();
            $table->string('warna', 200)->nullable();
            $table->decimal('greige', 18, 2);
            $table->decimal('finish', 18, 2);
            $table->decimal('size_finish', 18, 2);
            $table->decimal('qty', 18, 2);
            $table->decimal('total_qty', 18, 2);

            $table->integer('spk_rajut_id')->unsigned();
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->index('mesin_id');
            $table->index('material_id');
            $table->index('material_raw_id');
            $table->index('spk_rajut_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spk_rajut_details');
    }
}
