@extends('layouts.app')

@section('title', '마이페이지 - Active Women\'s Premium Store')

@section('content')
$(cat mypage-order-detail-main.html)
@endsection

@push('scripts')
$(cat mypage-order-detail-scripts.html)
@endpush
