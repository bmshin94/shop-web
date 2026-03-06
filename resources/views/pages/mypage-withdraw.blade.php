@extends('layouts.app')
@section('title', '마이페이지 - Active Women\'s Premium Store')
@section('content')
$(cat mypage-withdraw-main.html)
@endsection
@push('scripts')
$(cat mypage-withdraw-scripts.html)
@endpush
