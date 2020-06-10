@extends('templates.default')
@section('content')
<h3>Sign up</h3>
<div class="row">
  <div class="col-lg-6">
    <form class="form-vertical" role="form" method="post" action="{{route('auth.signup')}}">
      <div class="form-group">
        <label for="email" class="control-label">
          Your email address
        </label>
        <input class="form-control {{ $errors->has('email') ? ' is-invalid' : ''}}"  type="email" name="email" id="email" value="{{Request::old('email') ?: ''}}">
        @if ($errors->has('email'))
          <span class="help-block text-danger">
            {{ $errors->first('email') }}
          </span>
        @endif
      </div>
      <div class="form-group">
        <label for="username" class="control-label">
          Choose a username
        </label>
        <input class="form-control {{$errors->has('username') ? ' is-invalid' : ''}}" type="text" name="username" value="{{Request::old('username') ?: ''}}" id="username">
        @if ($errors->has('username'))
          <span class="help-block text-danger">
            {{$errors->first('username')}}
          </span>
        @endif
      </div>
      <div class="form-group">
        <label for="password" class="control-label">
          Choose a password
        </label>
        <input class="form-control {{ $errors->has('password') ? ' is-invalid' : ''}}" type="password" name="password" id="password">
        @if ($errors->has('password'))
          <span class="help-block text-danger">
            {{$errors->first('password')}}
          </span>
        @endif
      </div>

      <div class="form-group">
        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit" name="_token" value="{{ Session::token()}}">Submit</button>
      </div>
    </form>
  </div>
</div>
@endsection
