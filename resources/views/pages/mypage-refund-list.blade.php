@extends('layouts.app')

@section('title', '마이페이지 - Active Women\'s Premium Store')

@section('content')
$(cat mypage-refund-list-main.html)
@endsection

@push('scripts')
$(cat mypage-refund-list-scripts.html)
@endpush
