<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        //ここでメモデータを取得
        $memos = Memo::select('memos.*')
                        ->where('user_id', '=', \Auth::id())
                        ->whereNull('deleted_at')
                        ->orderBy('updated_at', 'desc')
                        ->get();

        return view('create', compact('memos'));
    }

    public function store(Request $request)//インスタンス化して使えるように
    {
        $posts = $request->all();

        Memo::insert(['content' => $posts['content'], 'user_id' => \Auth::id()]);

        return redirect( route('home') );
    }

    public function edit($id)
    {

        //ここでメモデータを取得
        $memos = Memo::select('memos.*')
                        ->where('user_id', '=', \Auth::id())
                        ->whereNull('deleted_at')
                        ->orderBy('updated_at', 'desc')
                        ->get();

        $edit_memo = Memo::find($id);

        return view('edit', compact('memos', 'edit_memo'));
    }

    public function update(Request $request)//インスタンス化して使えるように
    {
        $posts = $request->all();

        //whereで更新する箇所を指定
        Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content']]);

        return redirect( route('home') );
    }

    public function destroy(Request $request)
    {
        $posts = $request->all();

        //whereで更新する箇所を指定
        //Memo::where('id', $posts['memo_id'])->delete<-これやると物理削除になってしまう
        //updateメソッドで時間をdeleted_atに入れる
        Memo::where('id', $posts['memo_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);

        return redirect( route('home') );
    }
}
