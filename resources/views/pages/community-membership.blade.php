@extends('layouts.app')
@section('title', '커뮤니티 - Active Women\'s Premium Store')
@section('content')
$(cat community-membership-main.html)
@endsection
@push('scripts')
$(cat community-membership-scripts.html)
@endpush
