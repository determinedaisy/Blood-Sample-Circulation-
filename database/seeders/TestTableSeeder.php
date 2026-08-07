<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('test_table')->insert([
            ['message' => 'Hello'],
            ['message' => 'Blood sample received'],
            ['message' => 'Sample dispatched'],
        ]);
    }
}
