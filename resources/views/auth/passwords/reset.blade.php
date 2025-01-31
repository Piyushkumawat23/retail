@extends('frontend.layouts.app')

@section('content')

<!-- password reset start -->
<div class="customer_login">
    <div class="container">
        <div class="row">
        <!--reset password area start-->
            <div class="col-lg-6 col-md-6">
                <div class="account_form">
                <h2>{{ translate('Reset Password') }}</h2>
                <p class="mb-4 opacity-60">{{translate('Enter your email address and new password and confirm password.')}} </p>
                    <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                        <p>
                        <label>{{ translate('Email') }}</label>
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" placeholder="{{ translate('Email') }}" required autofocus>

                        @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                        </p>
                        <p>
                        <label>{{translate('Code')}}</label>
                        <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ $email ?? old('code') }}" placeholder="{{translate('Code')}}" required autofocus>

                        @if ($errors->has('code'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('code') }}</strong>
                        </span>
                        @endif
                        </p>
                        <p>
                        <label>{{ translate('New Password') }}</label>
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ translate('New Password') }}" required>

                        @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                        </p>
                        <p>
                        <label>{{ translate('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ translate('Confirm Password') }}" required>
                        </p>
                        <div class="login_submit">
                        <button type="submit">{{ translate('Reset Password') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        <!--reset password area start-->
        </div>
    </div>
</div>
<!-- customer login end -->
@endsection