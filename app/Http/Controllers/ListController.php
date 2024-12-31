<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShoppingListRegisterPostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Shopping_list as ShoppingListModel;
use Illuminate\Support\Facades\DB;
use App\Models\CompletedShoppingList as CompletedShoppingListModel;

class ListController extends Controller
{
    /**
     * 買い物リスト一覧ページ を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function list()
    {  
        // 1Page辺りの表示アイテム数を設定
        $per_page = 3;

        // 一覧の取得
        $list = ShoppingListModel::where('user_id', Auth::id())
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate($per_page);
                                    //->get();
        /*
        $sql = ShoppingListModel::where('user_id', Auth::id())
                                    ->orderBy('created_at', 'DESC')
                                    ->toSql();
        // echo "<pre>\n"; var_dump($sql, $list); exit;
        // var_dump($sql);
        */


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

    /**
     * 「買いもの」の完了
     */
    public function complete(Request $request, $shopping_list_id)
    {
        /* 「買いもの」を完了テーブルに移動させる */
        try {
            // トランザクション開始
            DB::beginTransaction();

            // shopping_list_idのレコードを取得する
            $shopping_list = ShoppingListModel::find($shopping_list_id);
            //$shopping_list = $this->getShoppingListModel($shopping_list_id);
            if ($shopping_list === null) {
                // task_idが不正なのでトランザクション終了
                throw new \Exception('');
            }

            // tasks側を削除する
            $shopping_list->delete();

            // completed_tasks側にinsertする
            $dask_datum = $shopping_list->toArray();
            unset($dask_datum['created_at']);
            unset($dask_datum['updated_at']);
            $r = CompletedShoppingListModel::create($dask_datum);
            if ($r === null) {
                // insertで失敗したのでトランザクション終了
                throw new \Exception('');
            }

            // トランザクション終了
            DB::commit();
            // 完了メッセージ出力
            $request->session()->flash('front.shopping_list_completed_success', true);

        } catch(\Throwable $e) {
            var_dump($e->getMessage()); exit;
            // トランザクション異常終了
            DB::rollBack();
            // 完了メッセージ出力
            $request->session()->flash('front.shopping_list_completed_failure', true);
        }
            // 一覧に遷移する
            return redirect('/shopping_list/list');

    }







    /**
     * 「買いもの」の削除処理
     */
    public function delete(Request $request, $shopping_list_id)
    {
        // shopping_list_idのレコードを取得する
        $shopping_list = ShoppingListModel::find($shopping_list_id);
       // $shopping_list = $this->getShoppingListModel($shopping_list_id);
        //var_dump($shopping_list); exit;
        // タスクを削除する
        if ($shopping_list !== null) {
            $shopping_list->delete();
            $request->session()->flash('front.shopping_list_delete_success', true);
        }
        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }





        /**
     * detail画面だど
     */
     public function detail($shopping_list_id){
        // shopping_list_idのレコードを取得する
        $shopping_list = ShoppingListModel::find($shopping_list_id);
        if ($shopping_list === null) {
            return redirect('/shopping_list/list');
        }
        // 本人以外のタスクならNGとする
        if ($shopping_list->user_id !== Auth::id()) {
            return redirect('/shopping_list/list');
        }

        var_dump($shopping_list); exit;
    }

}
