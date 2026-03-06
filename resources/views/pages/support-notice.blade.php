@extends('layouts.app')
@section('title', '고객센터 - Active Women\'s Premium Store')
@section('content')
$(cat support-notice-main.html)
@endsection
@push('scripts')
$(cat support-notice-scripts.html)
@endpush
