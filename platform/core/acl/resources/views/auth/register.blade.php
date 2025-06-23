@extends('core/acl::layouts.guest')

@section('content')
<div class="login-content">
    <div class="login-form">
        <div class="text-center mb-4">
            <h3>{{ trans('acl/auth.register.title') }}</h3>
        </div>

        {!! Form::open(['route' => 'access.register.post', 'class' => 'login-form-wrapper']) !!}
            <div class="form-group mb-3">
                <label class="control-label">{{ trans('acl/auth.register.first_name') }}</label>
                {!! Form::text('first_name', old('first_name'), [
                    'class' => 'form-control' . ($errors->has('first_name') ? ' is-invalid' : ''),
                    'placeholder' => trans('acl/auth.register.placeholder.first_name')
                ]) !!}
                {!! Form::error('first_name', $errors) !!}
            </div>

            <div class="form-group mb-3">
                <label class="control-label">{{ trans('acl/auth.register.last_name') }}</label>
                {!! Form::text('last_name', old('last_name'), [
                    'class' => 'form-control' . ($errors->has('last_name') ? ' is-invalid' : ''),
                    'placeholder' => trans('acl/auth.register.placeholder.last_name')
                ]) !!}
                {!! Form::error('last_name', $errors) !!}
            </div>

            <div class="form-group mb-3">
                <label class="control-label">{{ trans('acl/auth.register.username') }}</label>
                {!! Form::text('username', old('username'), [
                    'class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : ''),
                    'placeholder' => trans('acl/auth.register.placeholder.username')
                ]) !!}
                {!! Form::error('username', $errors) !!}
            </div>

            <div class="form-group mb-3">
                <label class="control-label">{{ trans('acl/auth.register.email') }}</label>
                {!! Form::email('email', old('email'), [
                    'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                    'placeholder' => trans('acl/auth.register.placeholder.email')
                ]) !!}
                {!! Form::error('email', $errors) !!}
            </div>

            <div class="form-group mb-3">
                <label class="control-label">{{ trans('acl/auth.register.password') }}</label>
                {!! Form::password('password', [
                    'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''),
                    'placeholder' => trans('acl/auth.register.placeholder.password')
                ]) !!}
                {!! Form::error('password', $errors) !!}
            </div>

            <div class="form-group mb-3">
                <label class="control-label">{{ trans('acl/auth.register.password_confirmation') }}</label>
                {!! Form::password('password_confirmation', [
                    'class' => 'form-control',
                    'placeholder' => trans('acl/auth.register.placeholder.password_confirmation')
                ]) !!}
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block btn-flat">
                    {{ trans('acl/auth.register.register') }}
                </button>
            </div>

            <div class="text-center">
                <p>
                    {{ trans('acl/auth.register.already_have_account') }}
                    <a href="{{ route('access.login') }}">{{ trans('acl/auth.register.login_here') }}</a>
                </p>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
