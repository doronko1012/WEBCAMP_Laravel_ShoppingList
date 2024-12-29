
@extends('layout')
{{-- メインコンテンツ --}}
@section('contents')
    <h1>indexログイン</h1>
    @if ($errors->any())
            <div>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            </div>
    @endif   
    <form action="/login" method="post">
        @csrf
        email：<input type="text" name="email"><br>
        パスワード：<input type="password" name="password"><br>
        <button>ログインする</button>
    </form>
@endsection