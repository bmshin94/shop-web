@extends('layouts.app')

@section('title', '마이페이지 - Active Women\'s Premium Store')

@section('content')
$(cat mypage-deposit-main.html)
@endsection

@push('scripts')
$(cat mypage-deposit-scripts.html)
@endpush
