<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterOptionWCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_options')->insert([
            ["kode" => "3101040", "deskripsi" => "Knitting", "tipe" => "WC", "created_by" => 1, "updated_by" => 1],
            ["kode" => "3201090", "deskripsi" => "Dyeing", "tipe" => "WC", "created_by" => 1, "updated_by" => 1],
            ["kode" => "3301010", "deskripsi" => "Finishing & Packing", "tipe" => "WC", "created_by" => 1, "updated_by" => 1]
        ]);
    }
}
