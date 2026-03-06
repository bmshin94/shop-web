@extends('layouts.app')
@section('title', 'Active Women\'s Premium Store')
@section('content')
$(cat review-write-main.html)
@endsection
@push('scripts')
$(cat review-write-scripts.html)
@endpush
