
@extends('layout')

{{-- メインコンテンツ --}}
@section('contents')
    <h1>TestIndexログイン</h1>
    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form action="/test/input" method="post">
        @csrf
        email：<input type="text" name="email" value="{{ old('email') }}"><br>
        パスワード：<input type="password" name="password"><br>
        <button>ログインする</button>
    </form>
@endsection