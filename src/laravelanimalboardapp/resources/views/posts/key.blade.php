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
  <h2>投稿完了フォーム</h2>
</header>
<main>
<form action="{{ route('delete') }}" method="POST">
    @csrf

    <table>
      <tr>
        <th>削除キー</th>
        <td>
          <input type="password" name="deleteKey" placeholder="削除キーを入力">
          <input type="hidden" name="id" value="{{ $id }}">
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <input type="submit" value="削除">
        </td>
      </tr>
    </table>
  </form>

</main>
</body>
</html>
