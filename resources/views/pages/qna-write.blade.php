@extends('layouts.app')
@section('title', 'Active Women\'s Premium Store')
@section('content')
$(cat qna-write-main.html)
@endsection
@push('scripts')
$(cat qna-write-scripts.html)
@endpush
