<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShoppingListRegisterPostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Shopping_list as ShoppingListModel;
use Illuminate\Support\Facades\DB;
use App\Models\CompletedShoppingList as CompletedShoppingListModel;


class CompletedShoppingListController extends Controller
{
     /**
     * 購入済み「買うもの」一覧ページ を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function list()
    {
        // 1Page辺りの表示アイテム数を設定
        $per_page = 3;

        // 一覧の取得
        $list = CompletedShoppingListModel::where('user_id', Auth::id())
        ->orderBy('shoppinglist', 'ASC')
        ->orderBy('created_at', 'ASC')
        ->paginate($per_page);
 
        return view('shopping_list.completed_list', ['list' => $list]);
    }
}
