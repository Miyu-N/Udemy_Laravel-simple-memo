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

            //自分のメモ取得はMemoモデルに任せる

            //インスタンス化
            $memo_model = new Memo();

            //メモ取得
            $memos = $memo_model->getMyMemo();

            //タグ取得
            $tags = Tag::where('user_id', '=', \Auth::id())
                        ->whereNull('deleted_at')
                        ->orderBy('id', 'desc')
                        ->get();

            $view->with('memos', $memos)
                ->with('tags', $tags);
        });
    }
}
