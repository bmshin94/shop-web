@extends('layouts.app')

@section('title', '마이페이지 - Active Women\'s Premium Store')

@section('content')
$(cat mypage-point-main.html)
@endsection

@push('scripts')
$(cat mypage-point-scripts.html)
@endpush
