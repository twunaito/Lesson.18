<!DOCTYPE html>

<html>


<head>

<meta charset='utf-8"'>

<link rel='stylesheet' href='/css/app.css'>

<meta name="viewport" content="width=device-width, initial-scale=1">

</head>


<body>


<header>
 <h1 class='page-title'>投稿画面</h1>
</header>

<div class='container'>
<div class="broad-outline">

<h2 class='page-header'>新しく投稿する</h2>

<!-- エラーメッセージを表示 -->
@if ($errors->any())
  <div class="alert alert-danger">
    <ul>
     @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
     @endforeach
    </ul>
  </div>
@endif

{!! Form::open(['url' => 'post/create']) !!}

<div class="form-group">

{!! Form::input('text', 'newPost', null, ['required', 'class' => 'form-control', 'placeholder' => '投稿内容']) !!}

</div>
<div class="btn-2">
 <a href="{{ route('posts.index') }}" class="btn btn-secondary">戻る</a>

 <button type="submit" class="btn btn-success pull-right">追加</button>
</div>

{!! Form::close() !!}

</div>
</div>


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

</body>


</html>
