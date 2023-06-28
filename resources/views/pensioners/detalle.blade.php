<?php
$filas = request()->query('balls');
?>
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">
        Datos de consultas
    </h2>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table id="example" class="display" style="min-width: 845px">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>DNI</th>
                                    <th>CIP</th>
                                    <th>Fecha de consulta</th>
                                    <th>Hora de consulta</th>
                                    <th>Hora de respuesta</th>
                                </tr>
                            </thead>
                            <tbody>
                            @for ($i = 0; $i < $filas; $i++)
                                <tr>
                                    <td>Marisa Luz Espinoza Espinoza</td>
                                    <td>10459716</td>
                                    <td>78945612</td>
                                    <td>13/06/2023</td>
                                    <td>10:08 am</td>
                                    <td>10:09 am</td>
                                </tr>
                            @endfor

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
