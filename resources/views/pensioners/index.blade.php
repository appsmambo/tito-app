@extends('layouts.app')
@section('content')

<div class="container">
    <h2 class="mb-3">
        Lista de Pensionistas
        <div class="float-end">
            <a class="btn btn-success" href="{{ route('pensioners.create') }}">Crear Pensionista</a> <a class="btn btn-secondary" href="{{ route('home') }}">Volver</a>
        </div>
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success mb-2">
                        {{ $message }}
                    </div>
                @endif
                <table class="table table-hover">
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
                {!! $pensioners->links() !!}
            </div>
        </div>
    </div>
</div>


@endsection
