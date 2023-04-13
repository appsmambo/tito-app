@extends('layouts.app')
@section('content')

<div class="container">
    <h2 class="mb-3">
        Nuevo Mensaje
        <div class="float-end">
            <a class="btn btn-secondary" href="{{ route('messages.index') }}"> Volver</a>
        </div>
    </h2>

    @if(session('status'))
    <div class="alert alert-success mb-1 mt-1">
        {{ session('status') }}
    </div>
    @endif

    <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Título:</strong>
                    <input type="text" name="title" class="form-control" placeholder="Título" maxlength="100" required>
                    @error('title')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Mensaje:</strong>
                    <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
                    @error('description')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary float-end">Grabar</button>
    </form>
</div>
@endsection
