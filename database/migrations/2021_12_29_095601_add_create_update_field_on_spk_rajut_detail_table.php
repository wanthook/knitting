<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreateUpdateFieldOnSpkRajutDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spk_rajut_details', function (Blueprint $table) {
            $table->integer('created_by', false, true)->nullable()->after('id');
            $table->integer('updated_by', false, true)->nullable()->after('created_by');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spk_rajut_details', function (Blueprint $table) {
            //
        });
    }
}
