@extends('layouts.app')
@section('title', '마이페이지 - Active Women\'s Premium Store')
@section('content')
$(cat mypage-profile-edit-main.html)
@endsection
@push('scripts')
$(cat mypage-profile-edit-scripts.html)
@endpush
