@extends('layouts.app')

@section('title', '마이페이지 - Active Women\'s Premium Store')

@section('content')
$(cat mypage-claim-list-main.html)
@endsection

@push('scripts')
$(cat mypage-claim-list-scripts.html)
@endpush
