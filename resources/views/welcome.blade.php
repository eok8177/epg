@extends('app')

@section('content')
<main class="text-center w-100">

  <div>
    <a href="/login" class="btn btn-outline-success" >Login</a>
    <a href="/register" class="btn btn-outline-info" >Register</a>
  </div>

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