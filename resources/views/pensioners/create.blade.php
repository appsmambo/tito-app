@extends('layouts.app')
@section('content')

<div class="container">
    <h2 class="mb-3">
        Nuevo Pensionista
        <div class="float-end">
            <a class="btn btn-secondary" href="{{ route('pensioners.index') }}"> Volver</a>
        </div>
    </h2>

    @if(session('status'))
    <div class="alert alert-success mb-1 mt-1">
        {{ session('status') }}
    </div>
    @endif

    <form action="{{ route('pensioners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="form-group">
                    <strong>DNI:</strong>
                    <input type="text" name="dni" class="form-control" placeholder="DNI" maxlength="8" required>
                    @error('dni')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="form-group">
                    <strong>Código CIP:</strong>
                    <input type="text" name="cip" class="form-control" placeholder="CIP" maxlength="20" required>
                    @error('cip')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="form-group">
                    <strong>Institución:</strong>
                    <select class="form-select" name="institution" required>
                        <option value="" selected>-Seleccione-</option>
                        <option value="EP">Ejército del Perú</option>
                        <option value="MGP">Marina de Guerra del Perú</option>
                        <option value="FAP">Fuerza Aérea del Perú</option>
                      </select>
                    @error('institution')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="form-group">
                    <strong>Nombres:</strong>
                    <input type="text" name="first_name" class="form-control" placeholder="Nombres" maxlength="255" required>
                    @error('first_name')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="form-group">
                    <strong>Apellidos:</strong>
                    <input type="text" name="last_name" class="form-control" placeholder="Apellidos" maxlength="255" required>
                    @error('last_name')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="form-group">
                    <strong>Fecha de nacimiento:</strong>
                    <input type="date" name="birth_date" class="form-control" placeholder="Fecha de nacimiento" required>
                    @error('birth_date')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="form-group">
                    <strong>Correo electrónico:</strong>
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" maxlength="255" required>
                    @error('email')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="form-group">
                    <strong>Teléfono:</strong>
                    <input type="tel" name="phone" class="form-control" placeholder="Teléfono" maxlength="9" required>
                    @error('phone')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary float-end">Grabar</button>
    </form>
</div>
@endsection
