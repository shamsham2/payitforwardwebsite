<?php

use Illuminate\Database\Seeder;

class config extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('config')->insert([
            'name' => 'max_local_link_age_days',
            'value' => '90',
            'created_at' => '1900-01-01 00:00:00',
            'updated_at' => '1900-01-01 00:00:00',
        ]);

        DB::table('config')->insert([
            'name' => 'check_and_store',
            'value' => 'true',
            'created_at' => '1900-01-01 00:00:00',
            'updated_at' => '1900-01-01 00:00:00',
        ]);

        DB::table('config')->insert([
            'name' => 'redirect_resolve_limit',
            'value' => '6',
            'created_at' => '1900-01-01 00:00:00',
            'updated_at' => '1900-01-01 00:00:00',
        ]);

    }
}
