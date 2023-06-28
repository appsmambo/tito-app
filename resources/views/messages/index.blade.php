@extends('layouts.app')
@section('content')

<div class="container">
    <h2 class="mb-3">
        Lista de Mensajes
        <div class="float-end">
            <a class="btn btn-success" href="{{ route('messages.create') }}">Crear Mensaje</a> <a class="btn btn-secondary" href="{{ route('home') }}">Volver</a>
        </div>
    </h2>
    <div class="card">
        <div class="card-body">
            <table id="example" class="table table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Mensaje</th>
                        <th width="280px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($messages as $message)
                        <tr>
                            <td>{{ $message->title }}</td>
                            <td>{{ $message->description }}</td>
                            <td>
                                <form action="{{ route('messages.destroy',$message->id) }}" method="Post">
                                    <a class="btn btn-primary" href="{{ route('messages.edit',$message->id) }}">Editar</a>
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
