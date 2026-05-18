@extends('admin.layout')
@section('title', 'Editar galería')
@section('heading', 'Editar: ' . $gallery->name)

@section('content')
    <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data" class="bg-white border border-ink-200 rounded-2xl p-6">
        @method('PUT')
        @include('admin.galleries._form')
    </form>
@endsection
