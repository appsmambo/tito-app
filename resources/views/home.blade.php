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
                        <table class="table student-data-table m-t-20">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nombre de consulta</th>
                                    <th>Total de consultas</th>
                                    <th>Costo de consulta</th>
                                    <th>Costo total</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Monto de pago del mes</td>
                                    <td>32</td>
                                    <td>$ 0000.12</td>
                                    <td>$ 0.384</td>
                                    <td>
                                        <a href="{{ url('/detalle?r=32') }}">
                                            <button type="button" class="btn btn-primary">Ver detalle</button>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Consultar estado de trámite o reclamo</td>
                                    <td>
                                        22
                                    </td>
                                    <td>
                                        $ 0.00012
                                    </td>
                                    <td>
                                        $ 0.00264
                                    </td>
                                    <td>
                                        <a href="{{ url('/detalle?r=22') }}">
                                            <button type="button" class="btn btn-primary">Ver detalle</button>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Solicitar boleta de pago</td>
                                    <td>
                                        10
                                    </td>
                                    <td>
                                        $ 0.00012
                                    </td>
                                    <td>
                                        $ 0.0012
                                    </td>
                                    <td>
                                        <a href="{{ url('/detalle?r=10') }}">
                                            <button type="button" class="btn btn-primary">Ver detalle</button>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Finalizar conversación</td>
                                    <td>
                                        7
                                    </td>
                                    <td>
                                        $ 0.00012
                                    </td>
                                    <td>
                                        $ 0.00084
                                    </td>
                                    <td>
                                        <a href="{{ url('/detalle?r=7') }}">
                                            <button type="button" class="btn btn-primary">Ver detalle</button>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Fecha de pago</td>
                                    <td>
                                        5
                                    </td>
                                    <td>
                                        $ 0.00012
                                    </td>
                                    <td>
                                        $ 0.0006
                                    </td>

                                    <td>
                                        <a href="{{ url('/detalle?r=5') }}">
                                            <button type="button" class="btn btn-primary">Ver detalle</button>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Registrar atención con el pensionista del área de pensiones</td>
                                    <td>
                                        4
                                    </td>
                                    <td>
                                        $ 0.00012
                                    </td>
                                    <td>
                                        $ 0.00048
                                    </td>

                                    <td>
                                        <a href="{{ url('/detalle?r=4') }}">
                                            <button type="button" class="btn btn-primary">Ver detalle</button>
                                        </a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Promedio de consultas Mensuales</h4>
                        </div>
                        <div class="card-body">
                            <div class="current-progress">
                                <div class="progress-content py-2">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="progress-text">Montos de Pago</div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="current-progressbar">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-primary w-90" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                                        90%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress-content py-2">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="progress-text">Boletas digitales</div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="current-progressbar">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-primary w-70" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                                        70%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress-content py-2">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="progress-text">Estado de reclamo o tramite</div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="current-progressbar">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-primary w-40" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                                        40%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6">

                    <div style="display: inline-block" class="col-xl-6 col-lg-6 col-sm-6 col-xxl-6 col-md-6">
                        <div class="card">
                            <div style="background-color: #593bdb;" class="social-graph-wrapper widget-linkedin">
                                <span class="s-icon"><i class="fa fa-clock-o">
                                <p style="font-size: 14px; font-family: sans-serif; font-weight: bold;">PROMEDIO <br>TIEMPO DE RESPUESTA</p></i></span>
                            </div>
                            <div class="row">
                                <div class="col-12 border-right">
                                    <div class="pt-3 pb-3 pl-0 pr-0 text-center">
                                        <h4 class="m-1"><span class="counter">0:05</span></h4>
                                        <p class="m-0">SEGUNDOS</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div style="display: inline-block" class="col-xl-5 col-lg-6 col-sm-6 col-xxl-6 col-md-6">
                        <div class="card">
                            <div style="background-color: #593bdb;" class="social-graph-wrapper widget-linkedin">
                                <span class="s-icon"><i class="fa fa-user">
                                <p style="font-size: 14px; font-family: sans-serif; font-weight: bold;">PROMEDIO <br>PENSIONISTAS ATENDIDOS</p></i></span>
                            </div>
                            <div class="row">
                                <div class="col-12 border-right">
                                    <div class="pt-3 pb-3 pl-0 pr-0 text-center">
                                        <h4 class="m-1"><span class="counter">8</span></h4>
                                        <p class="m-0">DIARIOS</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div class="row justify-content-center d-none">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Dashboard') }}

                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Evento</th>
                                <th scope="col">Interacciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($events as $event)
                            <tr>
                                <td>{{ $event->step }}</td>
                                <td>{{ $event->interacciones }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2">No existen registros.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Menu</th>
                                <th scope="col">Interacciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menus as $menu)
                            <tr>
                                <td>{{ $menu->menu }}</td>
                                <td>{{ $menu->interacciones }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2">No existen registros.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
