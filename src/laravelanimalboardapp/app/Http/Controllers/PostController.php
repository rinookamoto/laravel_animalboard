<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
        /**
     * トップページ
     *
     * @return \Illuminate\View\View
     */
    public function top()
    {
        // 全てのフォルダーを取得
        $posts = Post::leftJoin('replies', 'posts.id', '=', 'replies.board_id')

                ->select(
                    "posts.id AS p_id",
                    "posts.name AS p_name",
                    "posts.subject AS p_subject",
                    "posts.message AS p_message",
                    "posts.image_path AS p_image_path",
                    "posts.email AS p_email",
                    "posts.url AS p_url",
                    "posts.color AS p_color",
                    "posts.delete_key AS p_delete_key",
                    "posts.created_at AS p_created_at",

                    "replies.id AS r_id",
                    "replies.board_id AS r_board_id",
                    "replies.name AS r_name",
                    "replies.subject AS r_subject",
                    "replies.message AS r_message",
                    "replies.image_path AS r_image_path",
                    "replies.email AS r_email",
                    "replies.url AS r_url",
                    "replies.color AS r_color",
                    "replies.delete_key AS r_delete_key",
                    "replies.created_at AS r_created_at",
                    
                )
                ->orderBy('posts.id', 'desc')
                ->get();

        // 文字色を定義
        $colors = [
            "#CC0000",
            "#008000",
            "#0000FF",
            "#CC00CC",
            "#FF00CC",
            "#FF9933",
            "#000099",
            "#666666"
        ];

        // デフォルトの色を設定
        $defaultColor = $colors[0];

        // 文字色のチェック状態を決定
        $checked = array_map(function($colorCode) use ($defaultColor) {
            return $colorCode === old('color', $defaultColor) ? 'checked' : '';
        }, $colors);

        // 必要なデータをビューに渡して返します
        return view('posts/top', [
            'posts' => $posts,
            'colors' => $colors,
            'checked' => $checked
        ]);
    }

/**
 * 確認ページを表示します。
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\View\View
 */
public function confirm(Request $request)
{
    // フォームデータを抽出
    $data = $request->only([
        'preview', 'name', 'subject', 'message', 'email', 'url', 'color', 'delete_key', 'board_id'
    ]);

    // ファイルアップロード
    if ($request->hasFile('image_path')) {
        $file = $request->file('image_path');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/images', $filename);
        $data['image_path'] = $filename;
    } else {
        $data['image_path'] = ''; // 画像がアップロードされない場合
    }

    // プレビューが設定されている場合、確認ビューにデータを渡します
    if (isset($data['preview']) && $data['preview'] === "1") {
        return view('posts.confirm', $data);
    } else{
        return $this->saveThanks($request);
    }
}

    /**
     * フォルダーを保存します。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveThanks(Request $request)
    {
        // フォルダモデルのインスタンスを作成する
        $post = new Post();

        // それぞれにに入力値を代入する
        $post->preview = $request->preview;
        $post->name = $request->name;
        $post->subject = $request->subject;
        $post->message = $request->message;
        $post->email = $request->email;
        $post->url = $request->url;
        $post->color = $request->color;
        $post->delete_key = $request->delete_key;
        $post->image_path = $request->image_path;
        
        // 画像ファイルの処理
        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $filePath = $file->store('public/images'); // images ディレクトリに保存
            $post->image_path = basename($filePath); // ファイル名のみを保存
        }
        
        // インスタンスの状態をデータベースに書き込む
        $post->save();

        return redirect()->route('thanks', [
            'id' => $post->id,
        ]);

    }


    /**
     * 完了ページ
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function thanks(Request $request)
    {
        // フォームデータを抽出
        $data = $request->only([
            'preview', 'name', 'subject', 'message', 'email', 'url', 'color', 'delete_key', 'board_id', 'board_id'
        ]);

        // 画像ファイルの処理
        $filePath = null;
        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            // 画像ファイルをストレージに保存
            $filePath = $file->store('images', 'public');
        }

        // リクエストデータに画像パスを追加
        $data['image_path'] = $filePath;

        // 完了ビューにデータを渡して返します
        return view('posts/thanks', $data);
    }


        public function key(Request $request)
    {
        $id = $request->input('id');
        return view('posts.key')->with('id', $id);
    }

    public function editKey(Request $request)
    {
        $id = $request->input('id');

        // フォームデータを抽出
        $data = $request->only([
            'preview', 'name', 'subject', 'message', 'email', 'url', 'color', 'delete_key', 'board_id', 'board_id'
        ]);
        
        return view('posts.editKey', compact('id', 'data'));
    }


    public function delete(Request $request)
    {
        $id = $request->input('id');
        $post = Post::findOrFail($id);

        // フォームから送られてきた deleteKey と投稿の delete_key を比較
        $deleteKey = $request->input('deleteKey'); // フォームからの入力は deleteKey
        if ($post->deletePost($deleteKey)) {
            //echo 'yes';
            $post->delete();
            return redirect()->route('posts.top')->with('success', '投稿を削除しました。');
        } else {
            //echo 'no';
            //exit;
            return redirect()->back()->with('error', '削除キーが間違っています。');
        }
    }
    public function showEditForm(Request $request){
        $id = $request->input('id');
        $post = Post::findOrFail($id);

        // フォームから送られてきた deleteKey と投稿の delete_key を比較
        $deleteKey = $request->input('deleteKey'); // フォームからの入力は deleteKey
        if ($post->deletePost($deleteKey)) {
            //echo 'yes';
            return redirect()->route('edit');
        } else {
            //echo 'no';
            //exit;
            return redirect()->back()->with('error', '削除キーが間違っています。');
        }

    }

    public function edit(Request $request)
    {
        $id = $request->input('id');

        // モデルから該当の投稿を取得（例えば、Post モデルを想定しています）
        $post = Post::findOrFail($id);

        // 文字色を定義
        $colors = [
            "#CC0000",
            "#008000",
            "#0000FF",
            "#CC00CC",
            "#FF00CC",
            "#FF9933",
            "#000099",
            "#666666"
        ];
        
        // フォルダの画像パスをURL形式に変換
        if ($post->image_path) {

            $post->image_path = asset('storage/' . $post->image_path);

        }

        return view('posts.edit', compact('post', 'colors'));
        
    }

    public function showUpdateForm(Request $request, $id) {

        $id = $request->input('id');
        $post = Post::findOrFail($id);

        $data = $request->only([
            'preview',
            'name',
            'subject',
            'message',
            'email',
            'url',
            'color',
            'delete_key',
            'board_id',
            'board_id'
        ]);

        // 画像ファイルの処理
        $filePath = null;
        if ($request->hasFile('image_path')) {

            $file = $request->file('image_path');
            // 画像ファイルをストレージに保存
            $filePath = $file->store('images', 'public');

        }

        // リクエストデータに画像パスを追加
        $data['image_path'] = $filePath;


        return redirect()->route('update');

    }


    /**
     * 投稿の更新処理を行う
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
     public function update(Request $request)
     {
        // バリデーションまだ
        $id = $request->input('id');
        $post = Post::find($id);

        $post->name = $request->name;
        $post->subject = $request->subject;
        $post->message = $request->message;
        $post->email = $request->email;
        $post->url = $request->url;
        $post->color = $request->color;
        $post->delete_key = $request->delete_key;
        
        $deleteImage = $request->input('deleteImage');
        if ($deleteImage = "1") {
            $post->image_path = '';
        }

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $filePath = $file->store('public/images'); // images ディレクトリに保存
            $post->image_path = basename($filePath); // ファイル名のみを保存
        }

        $post->save();

        return view('posts/update', [
            'id' => $post->id,
        ]);
     }



}
