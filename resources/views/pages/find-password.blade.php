@extends('layouts.app')
@section('title', 'Active Women\'s Premium Store')
@section('content')
$(cat find-password-main.html)
@endsection
@push('scripts')
$(cat find-password-scripts.html)
@endpush
