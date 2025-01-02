<?php

declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterPostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// use Symfony\Component\HttpFoundation\StreamedRespomse;

class UserController extends Controller
{
    /**
     * ユーザーの新規登録画面を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('/user/register');
    }

    /**
     * ユーザーの新規登録
     * 
     * @return \Illuminate\View\View
     */
    public function register(UserRegisterPostRequest $request)
    {
        // validate済みのデータの取得
        $datum = $request->validated(); 
        $datum['password'] = Hash::make($datum['password']);
        // var_dump($datum); exit;

        // テーブルへのINSERT
        try{
            $r = UserModel::create($datum);
            // var_dump($r); exit;
        }catch(\Throwable $e){
            echo $e->getMessage();
            exit;
        }

        // ユーザー登録成功
        $request->session()->flash('front.user_register_sucsess', true);

        // リダイレクト
        return redirect('/');
    }   
}
