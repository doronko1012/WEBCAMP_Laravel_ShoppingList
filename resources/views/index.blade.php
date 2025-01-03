@extends('layout')
{{-- メインコンテンツ --}}
@section('contents')
    <h1>ログイン</h1>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    @endif
    <form action="/login" method="post">
        @csrf
        email：<input type="email" name="email"><br>
        パスワード：<input type="password" name="password"><br>
        <button>ログインする</button>
    </form>
            <a href="/user/register">会員登録</a>
@endsection