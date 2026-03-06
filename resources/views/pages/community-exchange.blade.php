@extends('layouts.app')
@section('title', '커뮤니티 - Active Women\'s Premium Store')
@section('content')
$(cat community-exchange-main.html)
@endsection
@push('scripts')
$(cat community-exchange-scripts.html)
@endpush
