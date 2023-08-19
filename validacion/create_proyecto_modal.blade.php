<?php
  use App\Models\Paises;
  $paises = Paises::all(['id_pais', 'nombre_pais']);
?>

<style>
    #map {
        height: 400px;
        /* The height is 400 pixels */
        width: 100%;
        /* The width is the width of the web page */
    }
</style>

<div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <i data-feather="layers" class="feather-icon feather-sm"> </i> <h4 class="modal-title" id="myLargeModalLabel"> Nuevo Proyecto</h4>              
                <button type="button" class="btn-close" data-dismiss="modal" ></button>
            </div>
            <form method="post" action="{{ route('proyectos.store') }}" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="modal-body">
                    <div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills nav-fill" role="tablist">
                         
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#navpill-111" role="tab">
                                    <span>Datos Generales</span>
                                </a>
                            </li>
                        
                            
                            <li class="nav-item">
                                <a class="particulares nav-link" data-bs-toggle="tab" href="#navpill-222" role="tab">
                                    <span>Datos Particulares</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="supervisores nav-link" data-bs-toggle="tab" href="#navpill-333" role="tab">
                                    <span>Datos Supervisor</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="particulares nav-link" data-bs-toggle="tab" href="#navpill-444" role="tab">
                                    <span>Agregar Beneficiarios</span>
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content border mt-2">

                            <div class="tab-pane active p-3" id="navpill-111" role="tabpanel">
                            <!--<div class="tab-pane p-3" id="navpill-111" role="tabpanel">-->
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control @error('folio_proyecto') is-invalid @enderror" placeholder="Folio" id="folio_proyecto" name="folio_proyecto" />
                                                    @error('folio_proyecto')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{$message}}</strong>
                                                        </span>
                                                    @enderror
                                                    <label>
                                                        <i data-feather="hash" class="feather-sm text-dark fill-white me-2"></i>
                                                        Folio Proyecto
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control @error('nombre_proyecto') is-invalid @enderror" placeholder="Nombre de la Obra" id="nombre_proyecto" name="nombre_proyecto" />
                                                    @error('nombre_proyecto')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{$message}}</strong>
                                                        </span>
                                                    @enderror
                                                    <label>
                                                        <i data-feather="user" class="feather-sm text-dark fill-white me-2"></i>
                                                        Nombre del Proyecto
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="row"> -->
                                            <div class="form">
                                                <label
                                                    ><i data-feather="edit-3" class="feather-sm text-dark fill-white me-2"></i
                                                    >Estatus</label
                                                >
                                                <div class="pt-1">
                                                    <select class="form-control col-12" id="estatus_proyecto" name="estatus_proyecto" required >
                                                    <option value="">--Seleccione--</option>
                                                        @foreach ($proyectos_estatus as $estatus_name => $estatus_value)
                                                        <option value="{{ $estatus_value }}">{{ $estatus_name }}</option>
                                                        @endforeach
                                                    </select>  
                                                </div>
                                            </div>
                                        <!-- </div> -->
                                        
                                       
                                        <div class="row mt-1">

                                            <div class="col-md-4 mt-3">
                                                <label
                                                    ><i data-feather="edit-3" class="feather-sm text-dark fill-white me-2"></i
                                                    >Tipo de proyecto</label
                                                >
                                                <div class="pt-1">
                                                    <select class="form-control col-12" id="tipo_obra_proyecto" name="tipo_obra_proyecto" required >
                                                        <option value="">--Seleccione--</option>  
                                                        @foreach ($proyectos_tipo_obras as $tipo_name => $tipo_value)
                                                        <option value="{{$tipo_value}}">{{$tipo_name}}</option>
                                                        @endforeach
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-3">
                                                <label
                                                    ><i data-feather="edit-3" class="feather-sm text-dark fill-white me-2"></i
                                                    >Modalidad del proyecto</label
                                                >
                                                <div class="pt-1">
                                                    <select class="form-control col-12" id="modalidad_obra_proyecto" name="modalidad_obra_proyecto" required >
                                                        <option value="">--Seleccione--</option>  
                                                        @foreach ($proyectos_modalidad_obras as $modalidad_name => $modalidad_value)
                                                        <option value="{{$modalidad_value}}">{{$modalidad_name}}</option>
                                                        @endforeach
                                                    </select>  
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-3">
                                                <label
                                                    ><i data-feather="edit-3" class="feather-sm text-dark fill-white me-2"></i
                                                    >Sector del proyecto</label
                                                >
                                                <div class="pt-1">
                                                    <select class="form-control col-12" id="sector_obra_proyecto" name="sector_obra_proyecto" required >
                                                        <option value="">--Seleccione--</option>  
                                                        @foreach ($proyectos_sector_obras as $sector_name => $sector_value)
                                                        <option value="{{$sector_value}}">{{$sector_name}}</option>
                                                        @endforeach
                                                    </select>  
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <label
                                                    ><i data-feather="map" class="feather-sm text-dark fill-white me-2"></i
                                                    >Estado</label
                                                >
                                                <div class="pt-1">
                                                    <select class="form-control" name="estado_proyecto" id="estado_proyecto" required >
                                                        <option value="">--Seleccione--</option>  
                                                        @foreach ($estados as $estado_name => $estado_value)
                                                            <option value="{{$estado_value}}">{{$estado_name}}</option>
                                                        @endforeach
                                                    </select>   
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <div class="pt-1 form-floating mb-3">
                                                    <input type="text" name="municipio_proyecto" id="municipio_proyecto" class="form-control" placeholder="Municipio" required />
                                                    <label
                                                        ><i data-feather="map" class="feather-sm text-dark fill-white me-2"></i
                                                        >Municipio</label
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="row"> -->
                                            <div class="form">
                                                <label
                                                    ><i data-feather="users" class="feather-sm text-dark fill-white me-2"></i
                                                    >Contratista</label
                                                >
                                                <div class="pt-1">
                                                    <select class="form-control" name="contratista_proyecto" id="contratista_proyecto" required >
                                                        <option value="">--Seleccione--</option>  
                                                        @foreach ($contratistas as $contratista_name => $contratista_value)
                                                            <option value="{{$contratista_value}}">{{$contratista_name}}</option>
                                                        @endforeach
                                                    </select>   
                                                </div>
                                            </div>
                                        <!-- </div> -->
                                        
   
                                    </div>
                                </div>
                            </div>

                            <div class="container tab-pane p-3" id="navpill-222" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12"> 

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="date" class="form-control" placeholder="Apellido Paterno" id="fecha_inicio_proyecto" name="fecha_inicio_proyecto" required />
                                                    <label>
                                                        <i data-feather="briefcase" class="feather-sm text-dark fill-white me-2"></i>
                                                        Inicio del proyecto
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="date" class="form-control" placeholder="Apellido Materno" id="fecha_finalizacion_proyecto" name="fecha_finalizacion_proyecto" required />
                                                    <label>
                                                        <i data-feather="briefcase" class="feather-sm text-dark fill-white me-2"></i>
                                                        Finalización del proyecto
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="container"> -->
                                            <!-- <div class="mt-5 row justify-content-center"> -->
                                                <fieldset class="border p-2 mt-1"> 

                                                    <legend class="text-primary">
                                                    </legend>

                                                    <div class="form-group">
                                                        <p class="text-secondary mt-3 mb-3 text-center">Mueve el Pin en la ubicación del proyecto</p>
                                                        <div id="map"></div>
                                                     </div> 

                                                    <input type="hidden" id="lat" name="lat_proyecto" value="{{old('lat')}}">
                                                    <input type="hidden" id="lng" name="lng_proyecto" value="{{old('lng')}}">

                                                    <div class="form-group mt-5">
                                                        <label for="formbuscador"> Verifica la dirección del proyecto</label>
                                                        <input
                                                            id="formbuscador"
                                                            type="text"
                                                            placeholder="Calle, No, Colonia, Municipio"
                                                            class="form-control"
                                                            name="direccion_proyecto"
                                                        >
                                                    </div>

                                                </fieldset>
                                            <!-- </div> -->
                                        <!-- </div> -->
                                        
                                        
                                    </div>
                                </div>
                            </div>



                            <!-- <div class="tab-pane active p-3" id="navpill-333" role="tabpanel"> -->
                            <div class="container tab-pane p-3" id="navpill-333" role="tabpanel">

                                <div class="btn-group mb-3" data-bs-toggle="buttons">
                                    <label class="btn btn-light-info text-info font-medium active">
                                        <div class="form-check">
                                            <input type="radio" id="customRadio4" name="customRadio" class="form-check-input" checked>
                                            <label class="form-check-label" for="customRadio4">Seleccionar Supervisor</label>
                                        </div>
                                    </label>
                                    <label class="btn btn-light-info text-info font-medium">
                                        <div class="form-check">
                                            <input type="radio" id="customRadio5" name="customRadio" class="form-check-input">
                                            <label class="form-check-label" for="customRadio5">Registrar Supervisor</label>
                                        </div>
                                    </label>
                                </div>
             
                                <div id="seleccionar_supervisor">
                                    <div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <label
                                                            ><i data-feather="user-plus" class="feather-sm text-dark fill-white me-2"></i
                                                            >Supervisor</label
                                                        >
                                                        <div class="pt-5">
                                                            <select
                                                                onchange="selSupervisor(this)"
                                                                class="form-control"
                                                                id="select_sup"
                                                            >
                                                                <option value="">--Seleccione --</option>
                                                                @foreach ($supervisores as $supervisor)
                                                                    <option class="opciones" value="{{$supervisor}}">{{$supervisor->empresa}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id_supervisor" id="id_supervisor">



                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-floating mb-3">
                                                    <input type="text"
                                                        name="empresa_supervisor_select"
                                                        class="form-control"
                                                        placeholder="Empresa"
                                                        id="empresa_supervisor_select" 
                                                        disabled
                                                        />
                                                    <label
                                                        ><i data-feather="home" class="feather-sm text-dark fill-white me-2"></i
                                                        >Nombre de la Empresa</label
                                                    >
                                                </div>
                                            </div>   
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-1">
                                                    <input type="text" name="representante_supervisor_select" class="form-control mt-2" id="representante_supervisor_select" placeholder="Representante" disabled />
                                                    <label
                                                        ><i data-feather="user" class="feather-sm text-dark fill-white me-2"></i
                                                        >Representante</label
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-1">
                                                    <input type="text" id="rfc_supervisor_select" name="rfc_supervisor_select" class="form-control mt-2" placeholder="RFC" disabled />
                                                    <label
                                                        ><i data-feather="user" class="feather-sm text-dark fill-white me-2"></i
                                                        >RFC</label
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 pt-3">
                                                <div class="form-floating mb-3">
                                                    <div class="pt-0">
                                                        <label><i class="ri-whatsapp-line"></i> Whatsapp</label>
                                                    </div>
                                                    <div class="pt-2">
                                                        <input class="form-control pb-1" type="tel" name="phone_select" id="phone_select" style="width: 100%;" disabled >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <label><i data-feather="phone" class="feather-sm text-dark fill-white me-2"></i>Telefono</label>
                                                    <div class="pt-5">
                                                        <input class="form-control" type="tel" name="telefono_supervisor_select" id="telefono_supervisor_select" pattern="[0-9]{10}" style="width: 100%;" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-floating mb-2">
                                                    <input type="email" id="correo_supervisor_select" name="correo_supervisor_select" class="form-control" placeholder="Correo" disabled />
                                                    <label
                                                        ><i data-feather="mail" class="feather-sm text-dark fill-white me-2"></i
                                                        >Correo</label
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <label
                                                        ><i data-feather="map" class="feather-sm text-dark fill-white me-2"></i
                                                        >País</label
                                                    >
                                                    <div class="pt-5">
                                                        <select
                                                            class="form-control"
                                                            name="pais_supervisor_select"
                                                            id="pais_supervisor_select"
                                                            disabled
                                                        >
                                                            <option value="">--Seleccione --</option>
                                                            @foreach ($paises as $pais)
                                                                <option
                                                                    value="{{ $pais->nombre_pais }}"
                                                                    >{{$pais->nombre_pais}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <label
                                                        ><i data-feather="map" class="feather-sm text-dark fill-white me-2"></i
                                                        >Estado</label
                                                    >
                                                    <div class="pt-5">
                                                        <select
                                                            class="form-control"
                                                            name="estado_supervisor_select"
                                                            id="estado_supervisor_select"
                                                            disabled
                                                        >
                                                            <option value="">--Seleccione --</option>
                                                            @foreach ($estados as $estado_name => $estado_value)
                                                                <option value="{{$estado_name}}">{{$estado_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" id="municipio_supervisor_select" name="municipio_supervisor_select" class="form-control" placeholder="Municipio" disabled />
                                                    <label
                                                        ><i data-feather="map-pin" class="feather-sm text-dark fill-white me-2"></i
                                                        >Municipio</label
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" id="direccion_supervisor_select" name="direccion_supervisor_select" class="form-control" placeholder="Dirección" disabled />
                                                    <label
                                                        ><i data-feather="compass" class="feather-sm text-dark fill-white me-2"></i
                                                        >Dirección</label
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form">
                                            <div class="form-floating mb-3">
                                                <div class="pt-5">
                                                    <div class="imagen_supervisor_ver col-md-4 d-flex align-items-center mb-1" id="imagen_supervisor_ver">

                                                    </div> 
                                                    <input type="hidden" id="imagen_supervisor_select" name="imagen_supervisor_select" />
                                                </div>
                                                <label
                                                    ><i data-feather="image" class="feather-sm text-dark fill-white me-2"></i
                                                    >Imagen</label
                                                >
                                            </div>
                                        </div>  

                                        

                                    </div>
                                </div>



                                <div id="registrar_supervisor">
                                    <div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-floating mb-3">
                                                    <input type="text"
                                                        name="empresa_supervisor"
                                                        class="form-control"
                                                        placeholder="Empresa"
                                                        id="empresa_supervisor" />
                                                    <label
                                                        ><i data-feather="home" class="feather-sm text-dark fill-white me-2"></i
                                                        >Nombre de la Empresa</label
                                                    >
                                                </div>
                                            </div>   
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-1">
                                                    <input type="text" name="representante_supervisor" class="form-control mt-2" id="representante_supervisor" placeholder="Representante" />
                                                    <label
                                                        ><i data-feather="user" class="feather-sm text-dark fill-white me-2"></i
                                                        >Representante</label
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-1">
                                                    <input type="text" id="rfc_supervisor" name="rfc_supervisor" class="form-control mt-2" placeholder="RFC" />
                                                    <label
                                                        ><i data-feather="user" class="feather-sm text-dark fill-white me-2"></i
                                                        >RFC</label
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 pt-3">
                                                <div class="form-floating mb-3">
                                                    <div class="pt-0">
                                                        <label><i class="ri-whatsapp-line"></i> Whatsapp</label>
                                                    </div>
                                                    <div class="pt-2">
                                                        <input class="form-control tell pb-1 inputintl" type="tel" name="phone" id="phone" style="width: 100%;" >
                                                        <input hidden type="text" id="codigo" name="codigo">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <label><i data-feather="phone" class="feather-sm text-dark fill-white me-2"></i>Telefono</label>
                                                    <div class="pt-5">
                                                        <input class="form-control" type="tel" name="telefono_supervisor" id="telefono_supervisor"  pattern="[0-9]{10}" style="width: 100%;" >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-floating mb-2">
                                                    <input type="email" id="correo_supervisor" name="correo_supervisor" class="form-control" placeholder="Correo" />
                                                    <label
                                                        ><i data-feather="mail" class="feather-sm text-dark fill-white me-2"></i
                                                        >Correo</label
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <label
                                                        ><i data-feather="map" class="feather-sm text-dark fill-white me-2"></i
                                                        >País</label
                                                    >
                                                    <div class="pt-5">
                                                        <select
                                                            class="form-control"
                                                            name="pais_supervisor"
                                                            id="pais_supervisor"
                                                        >
                                                            <option value="">--Seleccione --</option>
                                                            @foreach ($paises as $pais)
                                                                <option
                                                                    value="{{ $pais->nombre_pais }}"
                                                                    >{{$pais->nombre_pais}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <label
                                                        ><i data-feather="map" class="feather-sm text-dark fill-white me-2"></i
                                                        >Estado</label
                                                    >
                                                    <div class="pt-5">
                                                        <select
                                                            class="form-control"
                                                            name="estado_supervisor"
                                                            id="estado_supervisor"
                                                        >
                                                            <option value="">--Seleccione --</option>
                                                            @foreach ($estados as $estado_name => $estado_value)
                                                                <option value="{{$estado_name}}">{{$estado_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" id="municipio_supervisor" name="municipio_supervisor" class="form-control" placeholder="Municipio" />
                                                    <label
                                                        ><i data-feather="map-pin" class="feather-sm text-dark fill-white me-2"></i
                                                        >Municipio</label
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" id="direccion_supervisor" name="direccion_supervisor" class="form-control" placeholder="Dirección" />
                                                    <label
                                                        ><i data-feather="compass" class="feather-sm text-dark fill-white me-2"></i
                                                        >Dirección</label
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form">
                                            <div class="form-floating mb-3">
                                                <div class="pt-5">
                                                <input type="file" id="imagen_supervisor" name="imagen_supervisor" class="form-control" />
                                                </div>
                                                <label
                                                    ><i data-feather="image" class="feather-sm text-dark fill-white me-2"></i
                                                    >Imagen</label
                                                >
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>





                            
                            <div class="tab-pane p-3" id="navpill-444" role="tabpanel">
                                <div class="container-fluid">
                                    <div class="row">

                                        <!--
                                        <div class="col-lg-4 col-md-6">
                                            <div class="card customclass">
                                                <div class="border-bottom title-part-padding">
                                                    <h6 class="card-title mb-0">Inversión Inicial Programada</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <input
                                                            id="inversion_programada"
                                                            type="text"
                                                            value="0"
                                                            name="inversion_programada"
                                                            class="col-md-8 form-control"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="card customclass">
                                                <div class="border-bottom title-part-padding">
                                                    <h6 class="card-title mb-0">Estimaciones Programadas</h6>
                                                </div>
                                                <div class="card-body">
                                                    <input id="estimaciones_programadas" type="text" value="-1" name="estimaciones_programadas" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="card customclass">
                                                <div class="border-bottom title-part-padding">
                                                    <h6 class="card-title mb-0">Superviciones Programadas</h6>
                                                </div>
                                                <div class="card-body">
                                                    <input id="supervisiones_programadas" type="text" value="-1" name="supervisiones_programadas" />
                                                </div>
                                            </div>
                                        </div>
                                        -->

                                        <div class="col-lg-6">
                                            <!-- ---------------------
                                                start Beneficiarios Directos
                                            ---------------- -->
                                            <div class="card customclass">
                                                <div class="border-bottom title-part-padding">
                                                    <h6 class="card-title mb-0">Beneficiarios Directos</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p class="mb-0">Mujeres (cantidad)</p>
                                                    <input id="ben_directos_mujeres" type="text" value="-1" name="ben_directos_mujeres" />
                                                    <br>
                                                    <p class="mb-0">Hombres (cantidad)</p>
                                                    <input id="ben_directos_hombres" type="text" value="-1" name="ben_directos_hombres" />
                                                </div>
                                            </div>
                                            <!-- ---------------------
                                                end Beneficiarios Directos
                                            ---------------- -->
                                        </div>
                                        <div class="col-lg-6">
                                            <!-- ---------------------
                                                start Beneficiarios Indirectos
                                            ---------------- -->
                                            <div class="card customclass">
                                                <div class="border-bottom title-part-padding">
                                                    <h6 class="card-title mb-0">Beneficiarios Indirectos</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p class="mb-0">Mujeres (cantidad)</p>
                                                    <input id="ben_indirectos_mujeres" type="text" value="-1" name="ben_indirectos_mujeres" />
                                                    <br>
                                                    <p class="mb-0">Hombres (cantidad)</p>
                                                    <input id="ben_indirectos_hombres" type="text" value="-1" name="ben_indirectos_hombres" />
                                                </div>
                                            </div>
                                            <!-- ---------------------
                                                end Beneficiarios Indirectos
                                            ---------------- -->
                                        </div>
                                        
                                    </div>
                                    
                                </div>

                                <div class="modal-footer">
                                    <div class="mt-3 mt-md-0">
                                        <button type="submit" class="btn btn-primary font-medium waves-effect px-4" id="submit">
                                        <div class="d-flex align-items-center">
                                            <i data-feather="send" class="feather-sm fill-white me-2"></i>
                                            Guardar
                                        </div>
                                        </button>
                                        <button type="button" class=" btn btn-light-danger text-danger font-medium waves-effect text-start" data-dismiss="modal"> Cerrar</button>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>
