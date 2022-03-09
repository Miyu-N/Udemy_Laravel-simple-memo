<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Memo;
use App\Models\Tag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //すべてのメソッドが呼ばれる前に呼ばれるメソッド
        view()->composer('*', function($view) {
            $query_tag = \Request::query('tag');

            //もしクエリパラメータtagがあれば
            if( !empty($query_tag)) {
                //タグで絞り込み
                $memos = Memo::select('memos.*')
                        ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
                        ->where('memo_tags.tag_id', '=', $query_tag)
                        ->where('user_id', '=', \Auth::id())
                        ->whereNull('deleted_at')
                        ->orderBy('updated_at', 'desc')
                        ->get();

            }else{
                //タグがなければすべて取得
                $memos = Memo::select('memos.*')
                            ->where('user_id', '=', \Auth::id())
                            ->whereNull('deleted_at')
                            ->orderBy('updated_at', 'desc')
                            ->get();
            }

            $tags = Tag::where('user_id', '=', \Auth::id())
                        ->whereNull('deleted_at')
                        ->orderBy('id', 'desc')
                        ->get();

            $view->with('memos', $memos)
                ->with('tags', $tags);
        });
    }
}
