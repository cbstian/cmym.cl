<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('communes')->delete();
        DB::unprepared('ALTER TABLE communes AUTO_INCREMENT = 1;');

        DB::table('provinces')->delete();
        DB::unprepared('ALTER TABLE provinces AUTO_INCREMENT = 1;');

        DB::table('regions')->delete();
        DB::unprepared('ALTER TABLE regions AUTO_INCREMENT = 1;');

        DB::unprepared(File::get('resources/sql/regions_provinces_communes.sql'));
    }
}
