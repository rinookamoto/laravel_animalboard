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
<h2>返信</h2>
<!--内容表示-->
<table>
  <td>
        <a href="{{ $replies->email }}">
        {{ $replies->name }} さん</td>
        </a>
    </td>
    </tr>
    <tr>
        <td>
        <a href="{{ $replies->url }}">
            {{ $replies->subject }}
            </a>
        </td>
    </tr>
    <tr>
        <td style="color: {{$replies->color}}">
        {{$replies->message}}
        </td>
    </tr>
        @if(!empty($replies->image_path))
    <tr>
        <td>
            <img src="{{ asset('storage/images/' . $replies->image_path) }}">
        </td>
    </tr>
    @endif
    <tr style="background-color:#eee;">
        <td>{{ $replies->created_at->format('Y年m月d日 H時i分s秒') }}</td>
    </tr>
    <tr>
        <td class="button" colspan="2" align="right">
        <form action="{{ $replies->key }}" method="POST">
            @csrf
                <input type="hidden" name="id" value="{{ $replies->id }}">
                <input type="hidden" name="board_id" value="{{ $replies->board_id }}">
                <input name="delete" type="submit" value="削除">
        </form>
            <form action="{{ route('editKey') }}" method="POST">
            @csrf
                <input type="hidden" name="id" value="{{ $replies->id }}">
                <input type="hidden" name="board_id" value="{{ $replies->board_id }}">
                <input name="edit" type="submit" value="編集">
        </form>
        </td>
    </tr>
</table>

<!--返信入力-->
<form method="POST" action="{{ route('showRepConf') }} " enctype="multipart/form-data" >
@csrf

<table>
    <tr>
      <td><label for="name">名前</label></td>
      <td><input id="name" type="text" name="name" size="30" required value="{{ old('name') }}"></td>
    </tr>
    <tr>
      <td><label for="subject">件名</label></td>
      <td><input id="subject" type="text" name="subject" size="30" required value="RE : {{ old('subject') }}"></td>
    </tr>
    <tr>
      <td><label for="message">メッセージ</label></td>
      <td><textarea id="message" name="message" cols="30" rows="5" required>{{ old('message') }}</textarea></td>
    </tr>
    <tr>
      <td>画像</td>
      <td><input type="file" name="image_path"></td>
    </tr>
    <tr>
      <td><label for="email">メールアドレス</label></td>
      <td><input id="email" type="email" name="email" size="30" value="{{ old('email') }}"></td>
    </tr>
    <tr>
      <td><label for="user_url">URL</label></td>
      <td><input id="user_url" type="url" name="url" placeholder="http://" size="30" value="{{ old('url') }}"></td>
    </tr>
    <tr>
      <td>文字色</td>
      <td>
        @foreach($colors as $key => $colorCode)
          <label>
            <input type="radio" name="color" value="{{ $colorCode }}" {{ old('color', $colors[0]) == $colorCode ? 'checked' : '' }}>
            <big><span class="text-color" style="color: {{ $colorCode }};">■</span></big>
          </label>
        @endforeach
      </td>    
    </tr>
    <tr>
      <td><label for="pw">編集/削除キー</label></td>
      <td>
        <input id="pw" type="password" name="delete_key" minlength="4" maxlength="8" pattern="[a-zA-Z0-9]+" required>
        <small>（半角英数字のみで４～８文字）</small>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <label><input type="checkbox" name="preview" value="1" {{ old('preview') == '1' ? 'checked' : ''}}>プレビューする （投稿前に、内容をプレビューして確認できます）</label>
      </td>
    </tr>
    <tr>
      <td class="button" colspan="2">
      <input type="hidden" name="board_id" value="{{ $replies->id }}">
        <input name="board_insert" type="submit" value="投稿">
        <input type="reset" value="リセット">
      </td>
    </tr>
  </table>
  </form>
</main>
</body>
</html>
