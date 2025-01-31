@extends('frontend.layouts.app')

@section('content')


    <!-- password reset start -->
    <div class="customer_login">
        <div class="container">
            <div class="row">
            <!--login area start-->
                <div class="col-lg-6 col-md-6">
                    <div class="account_form">
                        <h2>{{ translate('Forgot Password?') }}</h2>
                        <p class="mb-4 opacity-60">{{translate('Enter your email address to recover your password.')}} </p>
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <p>
                            <label>{{ translate('Email') }}</label>
                                @if (addon_is_activated('otp_system'))

                                <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="{{ translate('Email or Phone') }}">

                                @else

                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email">
                                @endif
                                
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </p>
                            <div class="login_submit">
                            <button type="submit">{{ translate('Send Password Reset Link') }}</button>
                            </div>

                        </form>
                        <div class="mt-3">
                            <a href="{{route('user.login')}}" class="text-reset opacity-60">{{translate('Back to Login')}}</a>
                        </div>
                    </div>
                </div>
            <!--login area start-->
            </div>
        </div>
    </div>
    <!-- customer login end -->


@endsection
