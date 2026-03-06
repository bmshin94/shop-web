@extends('layouts.app')
@section('title', 'Active Women\'s Premium Store')
@section('content')
$(cat exhibition-main.html)
@endsection
@push('scripts')
$(cat exhibition-scripts.html)
@endpush
