@extends('backend.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('FAQ Management') }}</h5>
    </div>
    <div class="card-body">
        <a href="{{ route('website.faq.create') }}" class="btn btn-primary mb-3">{{ translate('Add New FAQ') }}</a>
        <table class="table table-bordered" style="text-align: center;">
            <thead>
                <tr>
                    <th>{{ translate('Category') }}</th>
                    <th>{{ translate('Question') }}</th>
                    <th>{{ translate('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($faqs as $faq)
                <tr>
                    <td>
                        @foreach ($categories as $category)
                            @if ($faq->category == $category->id)
                                {{ $category->name }}
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $faq->question }}</td>
                    <td>
                        <a href="{{ route('website.faq.edit', $faq->id) }}" class="btn btn-warning">{{ translate('Edit') }}</a>
                        <a href="{{ route('website.faq.delete', $faq->id) }}" class="btn btn-danger">{{ translate('Delete') }}</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
         <!-- Add Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $faqs->links() }}
        </div>
    </div>
</div>
@endsection
