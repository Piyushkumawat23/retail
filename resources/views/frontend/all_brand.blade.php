@extends('frontend.layouts.app')
    @section('meta')
    <link rel="canonical" href="https://bautlr.com/gemstones">
    @stop
@section('content')
<div class="breadcrumbs_area">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <div class="breadcrumb_content">
               <h2>
                 All Gemstones
               </h2>
               <ul>
                  <li> <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                  <li>&gt;</li>
                  <li> <a class="text-reset" href="{{ route('brands.all') }}">{{ translate('All Gemstones') }}</a></li>
                  <li>&gt;</li>
                  <li>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>

<?php /*
<section class="mb-4 gemstones-list">
    <div class="container">
        <div class="bg-white shadow-sm rounded px-3 pt-3">
            <div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-4 row-cols-md-3 row-cols-2 gutters-10">
                @foreach (\App\Models\Brand::where('active',1)->orderBy('name', 'asc')->get() as $brand)
                   <!-- <div class="col text-center">
                        <a href="{{ route('products.gemstone', $brand->slug) }}" class="d-block p-1 mb-3 border border-light rounded hov-shadow-md">
                            <img src="{{ uploaded_asset($brand->logo) }}" class="lazyload mx-auto h-300px  mw-100" alt="{{ $brand->getTranslation('name') }}">
                        </a>
                    </div> -->
					
					<div class="content">
						<a href="{{ route('products.gemstone', $brand->slug) }}" target="_blank">
						   <!--<div class="content-overlay"></div>
						  <img class="content-image lazyload mx-auto h-300px  mw-100" src="{{ uploaded_asset($brand->logo) }}"> -->
						  <div class="content-details ">
							<h3>{{ $brand->getTranslation('name') }} </h3>
							
							<!-- @if(!empty($brand->gemstone_month))
							<h6>{{ $brand->gemstone_month }} Birthstone</h6>
							@endif
							<p>{{ $brand->meta_description }}</p> -->
							
						  </div>
						</a>
					  </div>
					  
                @endforeach
            </div>
        </div>
    </div>
</section>
*/ ?>


<section class="mb-4 pt-5 pb-5 gemstones-list">
    <div class="container">
        <div class="px-3 pt-3">
            <div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-4 row-cols-md-3 row-cols-2 gutters-10">
                @foreach (\App\Models\Brand::where('active',1)->orderBy('name', 'asc')->get() as $brand)
                  
					<div class="col-lg-4 single_gemstone">
						
						  
						  <div class="content-details ">
						  <a href="{{ route('products.gemstone', $brand->slug) }}" target="_blank">
							<h3>{{ $brand->getTranslation('name') }} </h3>
							</a>
							</div>
						
					  </div>
					  
                @endforeach
				
            </div>
        </div>
    </div>
</section>

@endsection
