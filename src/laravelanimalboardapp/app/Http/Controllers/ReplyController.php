<?php

namespace App\Http\Controllers;

use App\Post;
use App\Reply;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function showReplyform(Request $request)
    {
        $id = $request->input('id');
        $replies = Post::findOrFail($id);
        return redirect()->route('replies/reply');
        }
    public function reply(Request $request)
    {
        $id = $request->input('id');
        $replies = Post::findOrFail($id);
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

        return view('replies/reply', [
            'replies' => $replies,
            'colors' => $colors,
            'checked' => $checked
        ]);
    }

    public function showRepConf(Request $request) 
    {
        return redirect()->route('replies/reply_confirm');
    }
    public function ReplyConf(Request $request)
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
            $data['image_path'] = ''; // 画像がアップロードされない場合にも 'image_path' キーを設定
        }


        // プレビューがチェックされていない場合
       // if ($request->input('preview') === '0') {
        $preview = $request->input('preview');

        if ($preview !== '1') {
            
            //
            $reply_id = $request->input('id');
            $reply = Reply::find($reply_id);
                    $reply = new Reply();

            // それぞれにに入力値を代入する
            //$post->preview = $request->preview;
            $reply->name = $request->name;
            $reply->subject = $request->subject;
            $reply->message = $request->message;
            $reply->email = $request->email;
            $reply->url = $request->url;
            $reply->color = $request->color;
            $reply->delete_key = $request->delete_key;
            $reply->image_path = $request->image_path;
            $reply->board_id = $request->board_id;
    
            // 画像ファイルの処理
            if ($request->hasFile('image_path')) {
                $file = $request->file('image_path');
                $filePath = $file->store('public/images'); // images ディレクトリに保存
                $reply->image_path = basename($filePath); // ファイル名のみを保存
            }
    
            // インスタンスの状態をデータベースに書き込む
            $reply->save();

            return view('replies/reply_confirm', [
                'boardid' => $reply->board_id,
            ]);
        }

        return view('replies/reply_confirm', $data);
        
    }

    public function replyShowThanks(Request $request) 
    {
        // フォームデータを抽出
        $data = $request->only([
            'preview', 'name', 'subject', 'message', 'email', 'url', 'color', 'delete_key', 'board_id'
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
        
        return redirect()->route('reply_thanks', $data);
    }
    public function replyThanks(Request $request) 
    {
        //Foreign Key: board_id   Local Key: id
        $reply_id = $request->input('id');
        $reply = Reply::find($reply_id);
                // リプライモデルのインスタンスを作成する
                //$reply = new Reply();
                $reply = new Reply();

                // それぞれにに入力値を代入する
                $reply->name = $request->name;
                $reply->subject = $request->subject;
                $reply->message = $request->message;
                $reply->email = $request->email;
                $reply->url = $request->url;
                $reply->color = $request->color;
                $reply->delete_key = $request->delete_key;
                $reply->image_path = $request->image_path;
                $reply->board_id = $request->board_id;
        
                // 画像ファイルの処理
                if ($request->hasFile('image_path')) {
                    $file = $request->file('image_path');
                    $filePath = $file->store('public/images'); // images ディレクトリに保存
                    $reply->image_path = basename($filePath); // ファイル名のみを保存
    }

    
        // インスタンスの状態をデータベースに書き込む
        $reply->save();

        return view('replies/reply_thanks', [
            'boardid' => $reply->board_id,
        ]);
    }

    /*public function showDeleteKey(Request $request) 
    {
        $board_id = $request->input('board_id');
        return redirect()->route('showreplyDeleteKey')->with('board_id', $board_id);
    }
*/
    public function replyDeleteKey(Request $request) 
    {
        //$board_id = $request->input('board_id');
        //return view ('replies/replyDeleteKey')->with('board_id', $board_id);
        $id = $request->input('id');
        return view('replies.replyDeletekey')->with('id', $id);

    }

    /*
    public function showReplyDelete(Request $request) 
    {
        $board_id = $request->input('board_id');
        echo 'hi';
        exit;

        return redirect()->route('replyDelete')->with('board_id', $board_id);
    }
*/
    public function replyDelete(Request $request)
    {
        /*
        $board['r_id'] = $request->input('id');
        $post = Reply::findOrFail($board['r_id']);
        // フォームから送られてきた deleteKey と投稿の delete_key を比較
        $deleteKey = $request->input('deleteKey'); // フォームからの入力は deleteKey
        if ($post->deletePost($deleteKey)) {
            //echo 'yes';            
            $post->delete();
            //return view('replies/replyDelete');
            return redirect()->route('replyDelete')->with('success', '投稿を削除しました。');
        } else {
            //echo 'no';
            //exit;
            return redirect()->back()->with('error', '削除キーが間違っています。');
        }
        //return view ('replies/replyDelete')->with('board_id', $board_id);
        */
        $id = $request->input('id');
        $reply = Reply::findOrFail($id);

        // フォームから送られてきた deleteKey と投稿の delete_key を比較
        $r_deleteKey = $request->input('r_deleteKey'); // フォームからの入力は r_deleteKey
        if ($reply->deletePost($r_deleteKey)) {
            //echo 'yes';
            $reply->delete();
            return redirect()->route('posts.top')->with('success', '投稿を削除しました。');
        } else {
            //echo 'no';
            //exit;
            return redirect()->back()->with('error', '削除キーが間違っています。');
        }

    }

    public function replyEditKey(Request $request)
    {
        $id = $request->input('id');
        // フォームデータを抽出
        $data = $request->only([
            'preview', 'name', 'subject', 'message', 'email', 'url', 'color', 'delete_key', 'board_id', 'board_id'
        ]);
        
        return view('replies.replyEditKey', compact('id', 'data'));
    }

    public function showReplyEditForm(Request $request)
    {
        $id = $request->input('id');
        $post = Reply::findOrFail($id);

        // フォームから送られてきた r_deleteKey と投稿の delete_key を比較
        $deleteKey = $request->input('r_deleteKey'); // フォームからの入力は r_deleteKey
        if ($post->deletePost($deleteKey)) {
            //echo 'yes';
            //echo 'hi';
            //exit;    
            return redirect()->route('replyEdit');
        } else {
            //echo 'no';
            //exit;
            return redirect()->back()->with('error', '削除キーが間違っています。');
        }

    }

    public function replyEdit(Request $request)
    {

        $id = $request->input('id');
        //echo 'hi';
        //exit;

        // モデルから該当の投稿を取得（例えば、Post モデルを想定しています）
        $post = Reply::findOrFail($id);

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
        
        return view('replies.replyEdit', compact('post', 'colors'));
    }

    public function showReplyUpdateForm(Request $request, $id){
        $id = $request->input('id');
        $post = Reply::findOrFail($id);

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


        return redirect()->route('replyUpdate');

    }

        /**
     * 投稿の更新処理を行う
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
     public function replyUpdate(Request $request)
     {
        // バリデーションまだ
        $id = $request->input('id');
        $post = Reply::find($id);

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



        return view('replies/replyUpdate', [
            'id' => $post->id,
        ]);
     }

}
