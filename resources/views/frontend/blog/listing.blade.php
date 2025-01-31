@extends('frontend.layouts.app')

@section('content')

    <div class="breadcrumbs_area" >
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <h1 class="fw-600 h4">{{ translate('Blog')}}</h1>                        
                    </div>
                </div>
            </div>
        </div>         
    </div>

<section class="pb-4">
    <div class="container">
        @if($blogs->isEmpty())
            <div class="text-center">
                <h2>No blogs found</h2>
                <p>You will be redirected to the home page</p>
            </div>

            <script>
                // Redirect to the home page after 5 seconds
                setTimeout(function() {
                    window.location.href = "{{ url('/') }}";
                }, 2000);  // 5000 milliseconds = 5 seconds
            </script>
        @else
            <div class="card-columns">
                @foreach($blogs as $blog)
                    <div class="card blogs-card mb-3 overflow-hidden shadow-sm">
                        <a href="{{ url("blog").'/'. $blog->slug }}" class="text-reset d-block">
                            <img
                                src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($blog->banner) }}"
                                alt="{{ $blog->title }}"
                                class="img-fluid lazyload "
                            >
                        </a>
                        <div class="p-4">
                            <h2 class="fs-18 fw-600 mb-1">
                                <a href="{{ url("blog").'/'. $blog->slug }}" class="text-reset">
                                    {{ $blog->title }}
                                </a>
                            </h2>
                            @if($blog->category != null)
                            <div class="mb-2 opacity-50">
                                <i>{{ $blog->category->category_name }}</i>
                            </div>
                            @endif
                            <p class="opacity-70 mb-4">
                                {{ Str::limit($blog->short_description, 100) }}
                            </p>
                            <a href="{{ url("blog").'/'. $blog->slug }}" class="btn btn-soft-primary">
                                {{ translate('View More') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="aiz-pagination aiz-pagination-center mt-4">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
</section>

@endsection
