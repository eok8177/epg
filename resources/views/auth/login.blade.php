@extends('auth.app')

@section('content')
<main class="form-signin text-center">
  <form  method="POST" action="/login">
    @csrf

    <div class="form-floating">
      <input
        id="email"
        type="email"
        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
        name="email"
        value="{{ old('email') }}"
        required
        autofocus
        placeholder="name@example.com"
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
        required
        placeholder="Password"
      >
      <label for="password">Password</label>
      @if ($errors->has('password'))
          <div class="invalid-feedback">
              <strong>{{ $errors->first('password') }}</strong>
          </div>
      @endif
    </div>

    <button class="w-100 btn btn-lg btn-outline-primary" type="submit">Login</button>

  </form>

  <a href="/register">Register</a>
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

    .form-signin input[type="email"] {
      margin-bottom: -1px;
      border-bottom-right-radius: 0;
      border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
      margin-bottom: 10px;
      border-top-left-radius: 0;
      border-top-right-radius: 0;
    }
</style>
@endpush