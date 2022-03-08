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
}
