@extends('layouts.app')
@section('title', '커뮤니티 - Active Women\'s Premium Store')
@section('content')
$(cat community-notice-main.html)
@endsection
@push('scripts')
$(cat community-notice-scripts.html)
@endpush
