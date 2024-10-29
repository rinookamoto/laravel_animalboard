<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
        /**
     * Custom method to delete a post based on a delete key.
     *
     * @param string $deleteKey
     * @return bool
     */

     protected $fillable = [
        'preview', 'name', 'subject', 'message', 'email', 'url', 'color', 'delete_key', 'image_path', 'board_id'
    ];

    /**
     * One-to-Many リレーション: 1つのフォルダに複数の返信がある
     */
    public function replies()
    {
        return $this->hasMany(Reply::class, 'board_id', 'id');
    }    

     public function deletePost($deleteKey)
    {
        // データベースの delete_key と比較
        return $deleteKey === $this->delete_key;
    }

}
