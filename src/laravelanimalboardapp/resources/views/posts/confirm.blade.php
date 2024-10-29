<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yield('title', '掲示板')</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<header>
  <nav class="my-navbar">
    <a class="my-navbar-brand" href="/">課題掲示板</a>
  </nav>
  <hr />
  <a href="/">TOP</a> | <a href="/">ワード検索</a>
  <hr />
  <h2>投稿確認フォーム</h2>
</header>
<main>
  <!-- プレビュー画面ver -->
<!--   @if(isset($preview) && $preview === "1") 
 -->
    <form action="{{ route('thanks') }}" method="POST">
      @csrf
      <!-- hiddenでthanksに渡す -->
      <input type="hidden" name="name" value="{{ $name }}">
      <input type="hidden" name="subject" value="{{ $subject }}">
      <input type="hidden" name="message" value="{{ $message }}">
      <input type="hidden" name="email" value="{{ $email }}">
      <input type="hidden" name="url" value="{{ $url }}">
      <input type="hidden" name="color" value="{{ $color }}">
      <input type="hidden" name="delete_key" value="{{ $delete_key }}">
      <input type="hidden" name="board_id" value="{{ $board_id }}">
      <input type="hidden" name="image_path" value="{{ $image_path }}"> <!-- 修正 -->

      <table>
          <tr>
              <th>名前</th>
              <td>{{ $name }}</td>
          </tr>
          <tr>
              <th>件名</th>
              <td>{{ $subject }}</td>
          </tr>
          <tr>
              <th>文字色</th>
              <td>
                <p style="color: {{ $color }};">■</p>
              </td>
          </tr>
          <tr>
              <th>メッセージ</th>
              <td style="color: {{ $color }};">
              {!! nl2br(e($message)) !!}
              </td>
          </tr>

          @if(!empty($image_path)) 
          <tr>
              <th>画像</th>
              <td>
              <img src="{{ asset('storage/images/' . $image_path) }}" alt="画像" width="100">
              </td>
          </tr>
          @endif
          <tr>
              <th>メールアドレス</th>
              <td>{{ $email }}</td>
          </tr>
          <tr>
              <th>URL</th>
              <td>{{ $url }}</td>
          </tr>
          <tr>
              <th>編集/削除キー</th>
              <td>{{ $delete_key }}</td>
          </tr>
          <tr>
              <td colspan="2" style="text-align:center;">

                  <!-- 戻るボタンで戻った時、入力した値を復元する為のコードはonclick="history.back();"だけで実装できる -->
                  <input type="button" value="戻る" onclick="history.back();">
                  <input type="submit" name="board_insert" value="投稿する">
              </td>
          </tr>
      </table>
    </form>

<!--    @else
     プレビューしないver 
        <br>
        <h1 align="center">投稿完了！！</h1>
        <h3 align="center"><a href="./">投稿画面に戻る</a></h3>
  @endif
 --> </main>
</body>
</html>


<!--
  asset() ヘルパー関数は、ウェブページで使う画像やファイルの場所を教えてくれる関数です。具体的には、画像やファイルがある場所のアドレス（URL）を教えてくれます。
-->
