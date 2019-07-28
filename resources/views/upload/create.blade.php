@extends('layouts.app')
@section('title', 'Загрузка фото ' . $product->name)
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3>
               <a href="https://rzk.com.ua/p/{{ $product->id }}">{{ $product->id }}
                {{ $product->name }}
              </a>
            </h3>
            <div class="card">
                <div class="card-header">Upload Main Photo</div>

                <div class="card-body">
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
                    
                    <div class="row justify-content-center mt-3">
                      <img src="https://s3.eu-central-1.amazonaws.com/rzk.com.ru/250.{{ $product->image_url }}" onerror="this.src = '//i1.rzk.com.ua/default/rzk_market_default.png';">
                    </div>
                    
                </div>
                <div class="card-footer">
                  <form action="{{ route('photos.upload.main', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="img">Выберите файл</label>
                        <input id="img" type="file" multiple name="file" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-default">Добавить</button>
                  </form>
                </div>
            </div>
            
        </div>
    </div>
    
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upload More Photos</div>

                <div class="card-body">
                    @foreach ($product->photos as $photo)
                      <div class="card mt-3">
                        <img class="card-img-top img-fluid" src="https://s3.eu-central-1.amazonaws.com/rzk.com.ru/800.{{ $photo->img }}" onerror="this.src = '//i1.rzk.com.ua/default/rzk_market_default.png';" alt="Card image cap">
                        <div class="card-footer">
                          <a href="{{ route('photos.upload.delete', ['img' => $photo->img]) }}" onclick="event.preventDefault();document.getElementById('file_id').value='{{ $photo->img }}';document.getElementById('delete-photo').submit();">
                          Удалить</a>
                        </div>
                      </div>
                    @endforeach
                    <form id="delete-photo" action="{{ route('photos.upload.delete') }}" method="POST" style="display: none;">
                        <input id="file_id" name="img">
                        @csrf
                        @method('DELETE')
                    </form>
                    
                </div>
                <div class="card-footer">
                  <form action="{{ route('photos.upload.more', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="img">Выберите файл</label>
                        <input id="img" type="file" multiple name="file[]" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-default">Добавить</button>
                  </form>
                </div>
            </div>
            
            
            
        </div>
    </div>
</div>
@endsection