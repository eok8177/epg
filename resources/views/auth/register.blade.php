@extends('auth.app')

@section('content')
<main class="form-signin text-center">
  <form method="POST" action="/register">
    @csrf

    <div class="form-floating">
      <input
        id="name"
        type="text"
        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
        name="name"
        value="{{ old('name') }}"
        placeholder="name"
        required
      >
      <label for="name">Name</label>
      @if ($errors->has('name'))
          <div class="invalid-feedback">
              <strong>{{ $errors->first('name') }}</strong>
          </div>
      @endif
    </div>

    <div class="form-floating">
      <input
        id="email"
        type="email"
        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
        name="email"
        value="{{ old('email') }}"
        placeholder="name@example.com"
        required
      >
      <label for="email">Email address</label>
      @if ($errors->has('email'))
          <div class="invalid-feedback">
              <strong>{{ $errors->first('email') }}</strong>
          </div>
      @endif
    </div>

    <div class="form-floating">
      <input
        id="password"
        type="password"
        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
        name="password"
        placeholder="Password"
        required
      >
      <label for="password">Password</label>
      @if ($errors->has('password'))
          <div class="invalid-feedback">
              <strong>{{ $errors->first('password') }}</strong>
          </div>
      @endif
    </div>

    <div class="form-floating">
      <input
        id="password_confirmation"
        type="password"
        class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
        name="password_confirmation"
        placeholder="Confirm Password"
        required
      >
      <label for="password_confirmation">Confirm Password</label>
      @if ($errors->has('password_confirmation'))
          <div class="invalid-feedback">
              <strong>{{ $errors->first('password_confirmation') }}</strong>
          </div>
      @endif
    </div>

    <button class="w-100 btn btn-lg btn-outline-primary" type="submit">Register</button>

  </form>

  <a href="/login">Login</a>
</main>
@endsection

@push('styles')
<style>
    body {
      display: flex;
      align-items: center;
      padding-top: 40px;
      padding-bottom: 40px;
      background-color: #f5f5f5;
    }

    .form-signin {
      width: 100%;
      max-width: 330px;
      padding: 15px;
      margin: auto;
    }


    .form-signin .form-floating:focus-within {
      z-index: 2;
    }

    .form-signin input {
      border-bottom-right-radius: 0;
      border-bottom-left-radius: 0;
    }
</style>
@endpush