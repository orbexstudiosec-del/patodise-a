@extends('admin.layout')
@section('title', 'Nueva galería')
@section('heading', 'Nueva galería')

@section('content')
    <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data" class="bg-white border border-ink-200 rounded-2xl p-6">
        @include('admin.galleries._form')
    </form>
@endsection
