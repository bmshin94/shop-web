@extends('layouts.app')
@section('title', '커뮤니티 - Active Women\'s Premium Store')
@section('content')
$(cat community-main.html)
@endsection
@push('scripts')
$(cat community-scripts.html)
@endpush
