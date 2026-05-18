@extends('admin.layout')
@section('title', 'Editar fotografía')
@section('heading', 'Editar: ' . $photo->title)

@section('content')
    <form method="POST" action="{{ route('admin.photos.update', $photo) }}" enctype="multipart/form-data" class="bg-white border border-ink-200 rounded-2xl p-6">
        @method('PUT')
        @include('admin.photos._form')
    </form>
@endsection
