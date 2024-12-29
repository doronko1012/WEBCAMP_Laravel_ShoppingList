
@extends('test.layout')

{{-- メインコンテンツ --}}
@section('contents')
        @csrf
        email：{{ $datum['email'] }}<br>
        パスワード：{{ $datum['password'] }}<br>
@endsection