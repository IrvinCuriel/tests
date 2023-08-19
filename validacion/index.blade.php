@extends('layouts.app')

@section('content')
  <!-- Start Datos Generales del modulo (Header) -->
  <div class="page-breadcrumb mb-3">
    <div class="row">
      <div class="card-body">
        <div class="row mb-0">
            @foreach( $status as $statu )
            <div class="col-lg-2 col-md-5 mb-2 mb-lg-0">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <div class="d-flex align-items-center p-4">
                            <div class="me-3">
                                <div data-label="{{ $statu["porcentaje"] }}%" class="css-bar mb-0-{{ $statu["color_porcentaje"] }} css-bar-{{ $statu["color_porcentaje"] }} css-bar-{{ $statu["step"] }}"></div>
                            </div>
                            <div>
                                <span>{{ $statu["name"] }}</span>
                                <h3 class="mb-0">{{ $statu["value"] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-5 align-self-center">
        <h4 class="page-title">Proyectos</h4>
        <div class="d-flex align-items-center">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Catálogos</a></li>
              <li class="breadcrumb-item active" aria-current="page">Proyectos</li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="col-7 align-self-center">
        <div class="d-flex no-block justify-content-end align-items-center">
          <div class="me-2">
            <div class="lastmonth"></div>
          </div>
          <div class="">
            <small>Número de Proyectos</small>
            <h4 class="text-info mb-0 font-medium">{{$proyectos->count()}}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Datos Generales del modulo (Header) -->
  <!-- Start Contenido del modulo -->

  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        @if ($permisos->crear == 1)
          <div class="row justify-content-center">
            <div class="col-md-12 d-grid gap-2">
                <a class="btn waves-effect waves-light btn-info create_proyecto_modal" data-toggle="modal" data-target="#smallModal" title="show">
                    <i data-feather="layers" class="feather-icon feather-sm"> </i> Nuevo Proyecto
                </a>
            </div>
          </div>
        @endif

        <br>
          {{-- {{ $proyectos }} --}}
        <br>

        <div class="card">
          <div class="border-bottom title-part-padding">
            <h4 class="card-title mb-0">Lista de proyectos del sistema</h4>
          </div>
          <div class="card-body">

            <h6 class="card-subtitle mb-3">
            </h6>
            <div class="table-responsive">
              <table
                id="multi_col_order"
                class="table table-striped table-bordered display text-nowrap custom-datatable"
                style="width: 100%"
              >
                <thead>
                  <!-- start row -->
                  <tr>
                    <th>Sector</th>
                    <th>Folio</th>
                    <th>Nombre del Proyecto</th>
                    <th>Tipo de Proyecto</th>
                    <th>Contratista</th>
                    <th>Inicio Proyecto</th>
                    <th>Fin de Proyecto</th>
                    <th class="collapsing">Ubicación</th>
                    <th class="collapsing">Estatus</th>
                    <th class="collapsing">Acciones</th>
                  </tr>
                  <!-- end row -->
                </thead>
                <tbody>
                  @foreach ($proyectos as $proyecto)
                  {{-- {{ dd($proyecto) }} --}}
                      <!-- start row -->
                    <tr>
                      <td>{{$proyecto->nombre_proyecto_sector_obra}}</td>
                      <td>{{$proyecto->folio_proyecto}}</td>
                      <td>{{$proyecto->nombre_proyecto}}</td>
                      <td>{{$proyecto->nombre_proyecto_tipo_obra}}</td>
                      <td>{{$proyecto->nombre}}</td>
                      @php
                      $inicio = $proyecto->fecha_inicio_proyecto;
                      $fecha_inicio_subs = substr( $inicio, 0, 10 );
                      $dateInicio = DateTime::createFromFormat( 'Y-m-d', $fecha_inicio_subs )->format('d/m/Y');
                      @endphp
                      <td>{{ $dateInicio }}</td>
                      @php
                      $finalizacion = $proyecto->fecha_finalizacion_proyecto;
                      $fecha_finalizacion_subs = substr( $finalizacion, 0, 10 );
                      $dateFin = DateTime::createFromFormat( 'Y-m-d', $fecha_finalizacion_subs )->format('d/m/Y');
                      @endphp
                      <td>{{ $dateFin }}</td>
                      <td>
                        <a class="btn waves-effect waves-light btn-info w-100 d-block text-light font-medium ubicacion_proyecto_modal" data-toggle="modal" data-target="#ubicacion_proyecto_modal" onclick="selProyecto( {{$proyecto}} )">
                            <i data-feather="map-pin" class="feather-icon feather-sm"> </i>  Ver Ubicación
                        </a>
                      </td>
                      <td>
                        @switch($proyecto->nombre_proyecto_estatus)
                            @case("Pre inversión")
                                <a class="btn d-flex btn-light-primary w-100 d-block text-primary font-medium">
                                  {{$proyecto->nombre_proyecto_estatus}}
                                </a>
                                @break
                            @case("Ejecución")
                                <a class="btn d-flex btn-light-info w-100 d-block text-info font-medium">
                                  {{$proyecto->nombre_proyecto_estatus}}
                                </a>
                                @break
                            @case("Concluido")
                                <a class="btn d-flex btn-light-success w-100 d-block text-success font-medium">
                                  {{$proyecto->nombre_proyecto_estatus}}
                                </a>
                                @break
                            @case("Suspendido")
                                <a class="btn d-flex btn-light-warning w-100 d-block text-warning font-medium">
                                  {{$proyecto->nombre_proyecto_estatus}}
                                </a>
                                @break
                            @case("Cancelado")
                                <a class="btn d-flex btn-light-danger w-100 d-block text-danger font-medium">
                                  {{$proyecto->nombre_proyecto_estatus}}
                                </a>
                                @break
                            @default

                        @endswitch

                      </td>
                      <td>
                        <div class="col-md-12">

                          <a class="btn waves-effect waves-light btn-info text-light font-medium ver_proyecto_modal" data-toggle="modal" data-target="#ver_proyecto_modal" data-id_proyecto="{{$proyecto->id_proyecto}}" >
                            <i data-feather="eye" class="feather-icon feather-sm"> </i> Visualizar
                          </a>

                          @if ($permisos->editar == 1)
                            <a class="btn waves-effect waves-light btn-success edit_proyecto_modal" data-toggle="modal" data-target="#edit_proyecto_modal" data-id_proyecto="{{$proyecto->id_proyecto}}">
                              <i data-feather="edit" class="feather-icon feather-sm"></i> Editar
                            </a>
                          @endif

                          @if ($permisos->eliminar == 1)
                            <a class="btn waves-effect waves-light btn-danger delete_proyecto_modal" data-toggle="modal" data-target="#delete_proyecto_modal" data-id_proyecto="{{$proyecto->id_proyecto}}">
                              <i data-feather="trash-2" class="feather-icon feather-sm"></i> Eliminar
                            </a>
                          @endif

                        </div>

                      </td>
                    </tr>
                    <!-- end row -->
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Contenido del modulo -->
  @include('Proyectos.Modals.create_proyecto_modal')
  @include('Proyectos.Modals.ubicacion_proyecto_modal')
  @include('Proyectos.Modals.edit_proyecto_modal')
  @include('Proyectos.Modals.delete_proyecto_modal')
  @include('Proyectos.Modals.ver_proyecto_modal')
  @include('Proyectos.Modals.agregar_beneficiario_modal')
  @include('Proyectos.Modals.editar_inversion_programada_modal')
  @include('Proyectos.Modals.editar_estimaciones_programadas_modal')
  @include('Proyectos.Modals.editar_supervisiones_programadas_modal')
  @push('body')
    <script src="js/proyectos.js"></script>
    @if ($errors->any())
    <script>
      $('#smallModal').modal('show');
    </script> 
    @endif
    @if (session()->has('success'))
      <script>
        $(document).ready(function () {
          message = {!!json_encode(session()->get('success'))!!}
          toastr.success(message, "¡Perfecto!", {
            closeButton: true,
            progressBar: true
          });
        });
      </script>
    @endif
    @if (session()->has('error'))
      <script>
        $(document).ready(function () {
          message = {!!json_encode(session()->get('error'))!!}
          toastr.error(message, "¡Error!", {
            closeButton: true,
            progressBar: true
          });
        });
      </script>
    @endif
  @endpush

@endsection
