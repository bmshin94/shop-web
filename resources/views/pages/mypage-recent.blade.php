@extends('layouts.app')
@section('title', '마이페이지 - Active Women\'s Premium Store')
@section('content')
$(cat mypage-recent-main.html)
@endsection
@push('scripts')
$(cat mypage-recent-scripts.html)
@endpush
