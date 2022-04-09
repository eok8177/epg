@extends('auth.app')

@section('content')
<main class="text-center w-100">

    <h1 class="title">Verify your email adress</h1>


    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            A fresh verification link has been sent to your email address.
        </div>
    @endif

    <p class="text">You must verify your email address to continue login. An email with a confirmation link was sent to your email.
    <p class="text">If you have not found letter in your inbox, please check your "Spam" folder or click the button below to resend your confirmation mail.

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
          <div class="form-group">
            <button type="submit" class="btn btn-outline-primary">Resend the letter</button>
        </div>
    </form>

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
</style>
@endpush