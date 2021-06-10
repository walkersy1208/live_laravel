<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order')->insert([
            'goods_id' => '1112221',
            'user_id' => '112221',
            'price' => 20,
            'remark' => '',
           // 'created_at' => \Carbon\Carbon::now()->toDateString(),
           // 'updated_at' => \Carbon\Carbon::now()->toDateString(),
        ]);
    }
}
