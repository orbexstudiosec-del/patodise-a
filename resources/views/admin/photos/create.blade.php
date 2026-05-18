@extends('admin.layout')
@section('title', 'Nueva fotografía')
@section('heading', 'Nueva fotografía')

@section('content')
    <form method="POST" action="{{ route('admin.photos.store') }}" enctype="multipart/form-data" class="bg-white border border-ink-200 rounded-2xl p-6">
        @include('admin.photos._form')
    </form>
@endsection
