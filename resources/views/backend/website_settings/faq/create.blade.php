@extends('backend.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Add New FAQ') }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('website.faq.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="category">{{ translate('Category') }}</label>
                <select name="category" id="category" class="form-control">
                    <option value="">{{ translate('Select Category') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="question">{{ translate('Question') }}</label>
                <input type="text" name="question" id="question" class="form-control">
            </div>
            <div class="form-group">
                <label for="answer">{{ translate('Answer') }}</label>
                <div class="col-md-8">
                    <textarea class="aiz-text-editor"
                    data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                    name="answer" id="answer"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-success">{{ translate('Save') }}</button>
        </form>
    </div>
</div>
@endsection
