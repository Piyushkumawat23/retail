@extends('backend.layouts.app')

@section('content')
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form action="{{ url('/admin/upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept="image/jpeg, image/png, application/pdf, application/msword">
        <button type="submit">Upload</button>
    </form>
@endsection