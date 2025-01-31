@extends('frontend.layouts.blank')

@section('content')
<section class="text-center py-6">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 mx-auto">
				<img loading="lazy" src="{{ static_asset('assets/img/restricted.jpg') }}" class="mw-50 mx-auto mb-5" style="width:50% !important;">
			    <h1 class="fw-700">{{ translate('Access Denied!') }}</h1>
			    <p class="fs-24 opacity-60">{{ translate("You Don't have permission to access this website.") }}</p>
			</div>
		</div>
    </div>
</section>
@endsection