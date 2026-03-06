@extends('layouts.app')
@section('title', 'Active Women\'s Premium Store')
@section('content')
$(cat event-main.html)
@endsection
@push('scripts')
$(cat event-scripts.html)
@endpush
