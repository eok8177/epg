@extends('app')

@section('content')


  <h1 class="text-center">User Cabinet</h1>

  <div class="container">

    <h5>08/04/2022</h5>

    <hr>
    
    <div class="row">
      <div class="col-sm-3">
        <input type="time" name="time" class="form-control mb-2" required>
      </div>
      <div class="col-sm-4">
        <input type="text" class="form-control mb-2" placeholder="Title">
      </div>
      <div class="col-sm-5">
        <input type="text" class="form-control mb-2" placeholder="Desription">
      </div>
    </div>

    <hr>
    <button type="button" class="btn btn-sm btn-outline-secondary">add next</button>

  </div>


@endsection

@push('styles')
<style>

</style>
@endpush