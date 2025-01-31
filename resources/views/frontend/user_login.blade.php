@extends('frontend.layouts.app')
@section('meta_title')<?php echo $page->meta_title; ?>@stop
@section('meta_description')<?php echo $page->meta_description; ?>@stop
  @section('meta')
    <link rel="canonical" href="https://bautlr.com/users/login">
    @stop
@section('content')
        <!-- customer login start -->
    <div class="customer_login">
        <div class="container">
            <div class="row">
               <!--login area start-->
                <div class="col-lg-6 col-md-6">
                    <div class="account_form">
                        <h2>{{ translate('Login')}}</h2>
                        <form role="form" action="{{ route('login') }}" method="POST">
                            @csrf
                            @if (addon_is_activated('otp_system') && env("DEMO_MODE") != "On")
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" id="email" autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            @else
                            <p>   
                            <label>Username or email <span>*</span></label>
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" id="email" autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                             </p>
                             <p>   
                                <label>Passwords <span>*</span></label>
                                <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ translate('Password')}}" name="password" id="password">
                             </p>   
                            <div class="login_submit col-sm-12">
                               <a href="{{ route('password.request') }}">Lost your password?</a>
                                <label for="remember">
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    {{  translate('Remember Me') }}
                                </label>
                                <div class="col-sm-12">                                    
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">{{  translate('Login') }}</button> 
                                </div>               
                            </div>
                            @endif
                            <input type="hidden" name='is_active' value="1">
                        </form>
                            <div class="text-center register_button">
                                <p class="text-muted mb-0">{{ translate('Dont have an account?')}}</p>
                                <!-- <a href="{{ route('user.registration') }}">{{ translate('Register Now')}}</a> -->
                                <button type="submit"><a href="{{ route('user.registration') }}" >{{ translate('Register Now')}}</a></button>

                            </div>
                     </div>    
                </div>
                <!--login area start-->
            </div>
        </div>    
    </div>
    <!-- customer login end -->
@endsection



@section('script')
    <script type="text/javascript">
        var isPhoneShown = true,
            countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phone-code");

        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            if(country.iso2 == 'bd'){
                country.dialCode = '88';
            }
        }

        var iti = intlTelInput(input, {
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
            onlyCountries: @php echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                if(selectedCountryData.iso2 == 'bd'){
                    return "01xxxxxxxxx";
                }
                return selectedCountryPlaceholder;
            }
        });

        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);

        input.addEventListener("countrychange", function(e) {
            // var currentMask = e.currentTarget.placeholder;

            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

        });

        function toggleEmailPhone(el){
            if(isPhoneShown){
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                $('input[name=phone]').val(null);
                isPhoneShown = false;
                $(el).html('{{ translate('Use Phone Instead') }}');
            }
            else{
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                $('input[name=email]').val(null);
                isPhoneShown = true;
                $(el).html('{{ translate('Use Email Instead') }}');
            }
        }



        function autoFillSeller(){
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }
        function autoFillCustomer(){
            $('#email').val('customer@example.com');
            $('#password').val('123456');
        }
        function autoFillDeliveryBoy(){
            $('#email').val('deliveryboy@example.com');
            $('#password').val('123456');
        }
    </script>
@endsection
