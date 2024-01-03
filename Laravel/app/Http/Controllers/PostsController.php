<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//データベース操作を行う
use Illuminate\Support\Facades\DB;

//Authファサードを使用する
use Illuminate\Support\Facades\Auth;

//日付および時間操作
use Carbon\Carbon;

//バリデーション機能を使用する
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    //トップ画面
    public function index()
    {
        //postsテーブルからすべてのレコード情報を取得する
        $list = DB::table('posts')->get();
        //取得したデータをビューに渡してposts.indexビューを表示する
        return view('posts.index', ['lists' => $list]);
    }


    //投稿画面
    public function createForm()
    {
        //posts.createFormを表示する
        return view('posts.createForm');
    }


    //投稿処理
    public function create(Request $request)
    {
        //バリデーションルールの定義(空白・文字列以外・100文字以上・半角全角スペースのみはNG)
        $rules = [
            'newPost' => ['required', 'string', 'max:100', 'regex:/[^\s　]/'],
        ];
        // カスタムエラーメッセージの定義
        $messages = [
            'newPost.required' => '投稿内容は必須です。',
            'newPost.string' => '投稿内容は文字列で入力してください。',
            'newPost.max' => '投稿内容は100文字以内で入力してください。',
            'newPost.regex' => '投稿内容は必須です。',
        ];
        // バリデーションの実行(指定されたバリデーションルールに基づいて検証)
        $validator = Validator::make($request->all(), $rules, $messages);
        // バリデーションが失敗した場合、リダイレクトしエラーメッセージを表示
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //現在のログインユーザーを取得
        $user = Auth::user();
        //ユーザー名を取得（存在しない場合はデフォルト値）
        $userName = $user ? $user->name : 'default_user';
        //現在の日付と時間を取得
        $now = Carbon::now();
        // フォームからの新しい投稿の内容を取得
        $post = $request->input('newPost');
        // テーブルに新しい投稿を挿入
        DB::table('posts')->insert([
            'user_name' => $userName,
            'contents' => $post,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        // 投稿が成功したら '/index' にリダイレクト
        return redirect('/index');
    }


    //投稿編集画面
    public function updateForm($id)
    {
        //postsテーブルからIDが$idに一致する最初のレコードを取得
        $post = DB::table('posts')
            ->where('id', $id)
            //条件と一致する最初のレコードを取得
            ->first();
        //posts.updateFormを表示する
        //post という変数に $post の内容を渡す
        return view('posts.updateForm', ['post' => $post]);
    }


    //投稿を編集し、データベースに保存
    public function update(Request $request)
    {
        //バリデーションルールの定義(空白・文字列以外・100文字以上・半角全角スペースのみはNG)
        $rules = [
            'upPost' => ['required', 'string', 'max:100', 'regex:/[^\s　]/'],
        ];
        // カスタムエラーメッセージの定義
        $messages = [
            'upPost.required' => '投稿内容は必須です。',
            'upPost.string' => '投稿内容は文字列で入力してください。',
            'upPost.max' => '投稿内容は100文字以内で入力してください。',
            'upPost.regex' => '投稿内容は必須です。',
        ];
        // バリデーションの実行(指定されたバリデーションルールに基づいて検証)
        $validator = Validator::make($request->all(), $rules, $messages);
        // バリデーションが失敗した場合、リダイレクトしエラーメッセージを表示
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //リクエストから投稿のIDを取得
        $id = $request->input('id');
        //リクエストから編集後の投稿内容を取得
        $up_post = $request->input('upPost');
        //現在の日付と時間を取得
        $now = Carbon::now();
        //postsテーブルの該当IDのレコードを更新
        DB::table('posts')
            ->where('id', $id)
            ->update([
                'contents' => $up_post,
                'updated_at' => $now
            ]);
        //indexにリダイレクト
        return redirect('/index');
    }


    //投稿を削除し、データベースに保存
    public function delete($id)
    {
        //postsテーブルから指定されたIDの投稿を削
        DB::table('posts')
            ->where('id', $id)
            ->delete();
        //indexにリダイレクト
        return redirect('/index');
    }


    //ログインしていない場合はログインページにリダイレクト
    public function __construct()
    {
        $this->middleware('auth');
    }


    //あいまい検索
    public function search(Request $request)
    {
        //リクエストから検索キーワードを取得
        $search = $request->input('search');
        //検索キーワードが空白または半角スペースの場合はすべての投稿を取得
        if (trim($search) === '') {
            $lists = DB::table('posts')->get();
        } else {
            //全角スペースのみの場合は検索を行わずすべての投稿を取得
            if (preg_match('/^[　\s]+$/', $search)) {
                $lists = DB::table('posts')->get();
            } else {
                //Eloquentクエリビルダを使用してデータベースからクエリの結果を取得
                $lists = DB::table('posts')
                    ->where('contents', 'like', '%' . $search . '%')
                    ->get();
            }
        }
        // 検索結果が0件の場合、その旨のメッセージを渡す
        $message = count($lists) === 0 ? '検索結果は0件です。' : null;
        //取得した結果をビューに渡して表示
        return view('posts.index', ['lists' => $lists, 'message' => $message]);
    }
}
