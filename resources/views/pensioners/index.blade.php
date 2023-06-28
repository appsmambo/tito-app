@extends('layouts.app')
@section('content')

<div class="container">
    <h2 class="mb-3">
        Lista de Pensionistas
        <div class="float-end">
            <a class="btn btn-success" href="{{ route('pensioners.create') }}">Crear Pensionista</a> <a class="btn btn-secondary" href="{{ route('home') }}">Volver</a>
        </div>
    </h2>
    <div class="card">
        <div class="card-body">
            <table id="example" class="table table-hover">
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>CIP</th>
                        <th>Institución</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Correo electrónico</th>
                        <th width="280px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pensioners as $pensioner)
                        <tr>
                            <td>{{ $pensioner->dni }}</td>
                            <td>{{ $pensioner->cip }}</td>
                            <td>{{ $pensioner->institution }}</td>
                            <td>{{ $pensioner->first_name }}</td>
                            <td>{{ $pensioner->last_name }}</td>
                            <td>{{ $pensioner->email }}</td>
                            <td>
                                <form action="{{ route('pensioners.destroy',$pensioner->id) }}" method="Post">
                                    <a class="btn btn-primary" href="{{ route('pensioners.edit',$pensioner->id) }}">Editar</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Borrar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection
