@extends('layouts.app')
@section('title', 'Active Women\'s Premium Store')
@section('content')
$(cat event-participate-main.html)
@endsection
@push('scripts')
$(cat event-participate-scripts.html)
@endpush
