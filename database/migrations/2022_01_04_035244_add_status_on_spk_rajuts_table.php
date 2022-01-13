<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusOnSpkRajutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spk_rajuts', function (Blueprint $table) {
            $table->enum('status',['unprocessed', 'processed', 'finished', 'cancelled'])->default('unprocessed')->after('total_qty');           
            $table->timestamp('status_at')->nullable();
            $table->text('history')->nullable()->comment('History dalam bentuk JSON');

            $table->index('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spk_rajuts', function (Blueprint $table) {
        });
    }
}
