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
<h2>編集</h2>
<form method="POST" action="{{ route('showUpdateForm', ['id' => $post->id]) }}" enctype="multipart/form-data">
  @csrf
<table>
    <tr>
      <td><label for="name">名前</label></td>
      <td><input id="name" type="text" name="name" size="30" required value="{{ old('name') ?? $post->name }}"></td>
    </tr>
    <tr>
      <td><label for="subject">件名</label></td>
      <td><input id="subject" type="text" name="subject" size="30" required value="{{ old('subject') ?? $post->subject }}"></td>
    </tr>
    <tr>
      <td><label for="message">メッセージ</label></td>
      <td><textarea id="message" name="message" cols="30" rows="5" required>{{ old('message') ?? $post->message }}</textarea></td>
    </tr>
    <tr>
      <th>画像</th>
      <td><input type="file" name="image_path"></td>
    </tr>
    <tr>
     @if ($post->image_path)
      <br>
      <td>※新しい画像をアップロードすると、古い画像は自動的に削除されます。</td>
    </tr>
    <tr>
       <td>▽現在の画像(<input type="checkbox" name="deleteImage" value="1">この画像を削除する)</td>
       <input type="hidden" name="deleteImage">
        <td>
          <img src="{{ asset($post->image_path) }}" alt="表示できません">
          @endif
        </td>
    </tr>
    <tr>
      <td><label for="email">メールアドレス</label></td>
      <td><input id="email" type="email" name="email" size="30" value="{{ old('email') ?? $post->email }}"></td>
    </tr>
    <tr>
      <td><label for="user_url">URL</label></td>
      <td><input id="user_url" type="url" name="url" placeholder="http://" size="30" value="{{ old('url') ?? $post->url }}"></td>
    </tr>
    <tr>
      <td>文字色</td>
      <td>
        @foreach($colors as $key => $colorCode)
          <label>
            <input type="radio" name="color" value="{{ $colorCode }}" {{ old('color', $post->color) == $colorCode ? 'checked' : '' }}>
            <big><span class="text-color" style="color: {{ $colorCode }}">■</span></big>
          </label>
        @endforeach
      </td>    </tr>
    <tr>
      <td><label for="pw">編集/削除キー</label></td>
      <td>
        <input id="pw" type="password" name="delete_key" minlength="4" maxlength="8" pattern="[a-zA-Z0-9]+" value="{{ old('delete_key') ?? $post->delete_key }}" required>
        <small>（半角英数字のみで４～８文字）</small>
      </td>
    </tr>
    <tr>
      <td colspan="2">※編集時はプレビュー機能を使えません</td>
    </tr>
    <tr>
      <td class="button" colspan="2">
        <input name="board_insert" type="submit" value="編集">
      </td>
    </tr>
  </table>
  </form>

</main>
</body>
</html>
