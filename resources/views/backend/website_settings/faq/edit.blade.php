@extends('backend.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Edit FAQ') }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('website.faq.update', $faq->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="category">{{ translate('Category') }}</label>
                <select name="category" id="category" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $faq->category == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="question">{{ translate('Question') }}</label>
                <input type="text" name="question" id="question" class="form-control" value="{{ $faq->question }}" required>
            </div>
            <div class="form-group">
                <label for="answer">{{ translate('Answer') }}</label>
                <textarea name="answer" 
                data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                id="answer" class="aiz-text-editor" required>{{ $faq->answer }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">{{ translate('Update') }}</button>
        </form>
    </div>
</div>
@endsection
