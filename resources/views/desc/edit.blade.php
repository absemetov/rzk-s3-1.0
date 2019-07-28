@extends('layouts.app')
@section('title', 'Описание ' . $product->name)
@section('content')
<div class="container">
    <h3>Описание {{ $product->name }}</h3>
    @if (count($errors) > 0)
      <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <form action="{{ route('desc.update', $product->id) }}" method="POST">
      @csrf
      <div class="form-group">
        <textarea class="form-control" name="desc" rows="10">{{ $product->description }}</textarea>
      </div>
      <button type="submit" class="btn btn-default">Save</button>
    </form>
    <p class="lead mt-3">
      {!! $product->description !!}
    </p>
</div>
@endsection