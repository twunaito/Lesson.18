<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = Auth::user(); // 現在のログインユーザーを取得
        $userName = $user ? $user->name : 'default_user'; // ユーザー名を取得（存在しない場合はデフォルト値）

        // 現在の日時を取得
        $now = Carbon::now();

        // 'contents' カラムも追加し、必要に応じて値を設定
        DB::table('posts')->insert([
            'user_name' => $userName,
            'contents' => '1つ目の投稿になります',
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
