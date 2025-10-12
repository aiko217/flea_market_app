<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            [
                'user_id' => rand(1, 10),
                'name' => '腕時計',
                'price' => '15000',
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'images/watch.jpg',
                'condition_id' => '1',
            ],
            [
                'user_id' => rand(1, 10),
                'name' => 'HDD',
                'price' => '5000',
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'images/Disk.jpg',
                'condition_id' => '2',
            ],
            [
                'user_id' => rand(1, 10),
                'name' => '玉ねぎ3束',
                'price' => '300',
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'images/Onion.jpg',
                'condition_id' => '3',
            ],
            [
                'user_id' => rand(1, 10),
                'name' => '革靴',
                'price' => '4000',
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'image' => 'images/Shoes.jpg',
                'condition_id' => '4',
            ],
            [
                'user_id' => rand(1, 10),
                'name' => 'ノートPC',
                'price' => '45000',
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'image' => 'images/pc.jpg',
                'condition_id' => '1',
            ],
            [
                'user_id' => rand(1, 10),
                'name' => 'マイク',
                'price' => '8000',
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image' => 'images/Mic.jpg',
                'condition_id' => '2',
            ],
            [
                'user_id' => rand(1, 10),
                'name' => 
                'ショルダーバッグ',
                'price' => '3500',
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'images/bag.jpg',
                'condition_id' => '3',
            ],
            [
                'user_id' => rand(1, 10),
                'name' => 'タンブラー',
                'price' => '500',
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'image' => 'images/Tumbler.jpg',
                'condition_id' => '4',
            ],
            [
                'user_id' => rand(1, 10),
                'name' => 'コーヒーミル',
                'price' => '4000',
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image' => 'images/Coffee.jpg',
                'condition_id' => '1',
            ],
            [
                'user_id' => rand(1, 10),
                'name' => 'メイクセット',
                'price' => '2500',
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'image' => 'images/makeup.jpg',
                'condition_id' => '2',
            ],

        ]);
    }
}
