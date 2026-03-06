@extends('layouts.app')
@section('title', '고객센터 - Active Women\'s Premium Store')
@section('content')
$(cat support-main.html)
@endsection
@push('scripts')
$(cat support-scripts.html)
@endpush
