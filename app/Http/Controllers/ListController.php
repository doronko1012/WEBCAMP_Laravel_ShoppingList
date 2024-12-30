<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShoppingListRegisterPostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Shopping_list as ShoppingListModel;

class ListController extends Controller
{
    /**
     * 買い物リスト一覧ページ を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function list()
    {
        // 一覧の取得
        $list = ShoppingListModel::where('user_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        $sql = ShoppingListModel::where('user_id', Auth::id())->orderBy('created_at', 'DESC')->toSql();
        // echo "<pre>\n"; var_dump($sql, $list); exit;
        // var_dump($sql);

        return view('shopping_list.list', ['list' => $list]);
    }

        /**
     * 買い物リストの新規登録
     */
    public function register(ShoppingListRegisterPostRequest $request)
    {
        // validate済みのデータの取得
        $datum = $request->validated();
        //$user = Auth::user();
        //$id = Auth::id();
        //var_dump($datum, $user, $id); exit;

        // user_id の追加
        $datum['user_id'] = Auth::id();

        // テーブルへのINSERT
        try{
            $r = ShoppingListModel::create($datum);
            //var_dump($r); exit;
        } catch(\Throwable $e) {
            echo $e->getMessage();
            exit;
        }

        // 「買いものリスト」登録成功
        $request->session()->flash('front.shopping_list_register_success', true);

        // リダイレクト
        return redirect('/shopping_list/list');
    }
}
