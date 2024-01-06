@extends('layouts.app')
@section('content')

<body>

<header>
 <h1 class='top-title'>トップ画面</h1>
</header>


<div class='container'>
<div class="broad-outline">

  <!-- あいまい検索の実装 -->
  <div class="row mb-3">
    <div class="col-md-6">
        <!-- posts.searchルートへデータを送信する -->
        <form action="{{ route('posts.search') }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="投稿内容を検索">
                <button type="submit" class="btn btn-primary">検索</button>
            </div>
        </form>
    </div>
  </div>

  <!-- 投稿ボタンの実装 -->
  <p class="pull-right"><a class="btn btn-success" href="/create-form">投稿する</a></p>
 <h2 class='page-header'>投稿一覧</h2>
@if (isset($message))
    <p>{{ $message }}</p>
@else
 <table class='table table-hover'>

 <tr>

  <th>名前</th>

  <th>投稿内容</th>

  <th>投稿日時</th>

 </tr>

 @foreach ($lists as $list)

 <tr>

 <td style="display: none;">{{ $list->id }}</td>

 <td>{{ $list->user_name }}</td>

 <td>{{ $list->contents }}</td>

 <td>{{ $list->created_at }}</td>


<!-- ユーザーがログインしており、かつログインしているユーザーの名前が表示されている投稿（$list）のユーザー名と一致する場合 -->
@if(Auth::check() && Auth::user()->name == $list->user_name)
<!-- 更新ボタンの実装 -->
    <td><a class="btn btn-primary" href="/post/{{ $list->id }}/update-form">更新</a></td>
    <!-- 削除ボタンの実装 -->
    <td><a class="btn btn-danger" href="/post/{{ $list->id }}/delete"
        onclick="return confirm('こちらの投稿を削除してもよろしいでしょうか？')">削除</a></td>
@endif

 </tr>

 @endforeach

 </table>
@endif

</div>
</div>


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</body>


@endsection
