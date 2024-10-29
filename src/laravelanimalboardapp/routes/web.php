<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PostController@top')->name('posts.top');
Route::post('/', 'PostController@top')->name('posts.top');

Route::get('/confirm', 'PostController@confirm')->name('confirm');
Route::post('/confirm', 'PostController@confirm');

Route::get('/thanks', 'PostController@thanks')->name('thanks');
Route::post('/thanks', 'PostController@saveThanks');

Route::get('/key', 'PostController@key')->name('key');
Route::post('/key', 'PostController@key');

Route::get('/editKey', 'PostController@editKey')->name('editKey');
Route::post('/editKey', 'PostController@editKey');

Route::get('/delete', 'PostController@delete')->name('delete');
Route::post('/delete', 'PostController@delete');

Route::get('/edit', 'PostController@showEditForm')->name('showEditForm');
Route::post('/edit', 'PostController@edit');

Route::get('/update', 'PostController@showUpdateForm')->name('showUpdateForm');
Route::post('/update', 'PostController@update');

//返信

Route::get('/reply', 'ReplyController@showReplyForm')->name('showReplyForm');
Route::post('/reply', 'ReplyController@reply');

Route::get('/reply_confirm', 'ReplyController@showRepConf')->name('showRepConf');
Route::post('/reply_confirm', 'ReplyController@ReplyConf');

Route::get('/reply_thanks', 'ReplyController@replyShowThanks')->name('replyShowThanks');
Route::post('/reply_thanks', 'ReplyController@replyThanks');

Route::get('/replyDeleteKey', 'ReplyController@replyDeleteKey')->name('replyDeleteKey');
Route::post('/replyDeleteKey', 'ReplyController@replyDeleteKey');

Route::get('/replyDelete', 'ReplyController@replyDelete')->name('replyDelete');
Route::post('/replyDelete', 'ReplyController@replyDelete');

Route::get('/replyEditKey', 'ReplyController@replyEditKey')->name('replyEditKey');
Route::post('/replyEditKey', 'ReplyController@replyEditKey');

Route::get('/replyEdit', 'ReplyController@showReplyEditForm')->name('showReplyEditForm');
Route::post('/replyEdit', 'ReplyController@replyEdit');

Route::get('/replyUpdate', 'ReplyController@showReplyUpdateForm')->name('showReplyUpdateForm');
Route::post('/replyUpdate', 'ReplyController@replyUpdate');

//ワード検索
Route::get('/search', 'SearchController@search')->name('search');
Route::post('/search', 'SearchController@search');

Route::get('/searchResult', 'SearchController@showSearchResult')->name('showSearchResult');
Route::post('/searchResult', 'SearchController@searchResult');
