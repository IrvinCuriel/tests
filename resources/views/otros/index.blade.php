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
              <h4 class="page-title">Minutas</h4>
              <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dictec</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Minutas</li>
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
                  <small>Minutas</small>
                  <h4 class="text-info mb-0 font-medium">{{$minutas->count()}}</h4>
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
                      <a class="btn waves-effect waves-light btn-info create_minuta_modal" data-toggle="modal" data-target="#create_minuta_modal" title="show">
                          <i data-feather="file-plus" class="feather-icon feather-sm"></i> Nueva Minuta
                      </a>
                  </div>
                </div>
              @endif

              <br>
              <div class="card">
                <div class="border-bottom title-part-padding">
                  <h4 class="card-title mb-0">Lista de minutas</h4>
                </div>
                <div class="card-body">

                  <div class="table-responsive">
                    <table
                      id="multi_col_order"
                      class="table table-striped table-bordered display text-nowrap custom-datatable"
                      style="width: 100%"
                    >
                      <thead>
                        <!-- start row -->
                        <tr>
                          <th>Nombre proyecto</th>
                          <th>No Minuta</th>
                          <th>Descripción</th>
                          <th>Fecha</th>
                          <th class="collapsing">Acciones</th>
                        </tr>
                        <!-- end row -->
                      </thead>
                      <tbody>
                      @foreach ($minutas as $minuta)
                          <!-- start row -->
                          <tr>
                            <td>{{$minuta->nombre_proyecto}}</td>
                            <td>{{$minuta->numero}}</td>
                            <td>{{$minuta->descripcion}}</td>
                            @php
                            $minutaFecha = $minuta->fecha;
                            $minutaFecha_subs = substr( $minutaFecha, 0, 10 );
                            $dateminutaFecha = DateTime::createFromFormat( 'Y-m-d', $minutaFecha_subs )->format('d/m/Y');
                            @endphp
                            <td>{{$dateminutaFecha}}</td>
                            <td>
                              <div class="col-md-12">
                                <a class="btn waves-effect waves-light btn-primary enviar_correo_modal" data-toggle="modal" data-target="#enviar_correo_modal" data-id_minuta="{{$minuta->id_minuta}}" onclick="obtener_correos_asistentes( {{$minuta->id_minuta}} )">
                                  <i data-feather="mail" class="feather-icon feather-sm"></i> Correo
                                </a>
                                @if ($permisos->editar == 1)
                                  <a class="btn waves-effect waves-light btn-success edit_minuta_modal" data-toggle="modal" data-target="#edit_minuta_modal" data-id_minuta="{{$minuta->id_minuta}}">
                                    <i data-feather="edit" class="feather-icon feather-sm"></i> Editar
                                  </a>
                                @endif

                                @if ($permisos->eliminar == 1)
                                  <a class="btn waves-effect waves-light btn-danger delete_minuta_modal" data-toggle="modal" data-target="#delete_minuta_modal" data-id_minuta="{{$minuta->id_minuta}}">
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
        @include('Minutas.Modals.enviar_correo_modal')
        @include('Minutas.Modals.create_minuta_modal')
        @include('Minutas.Modals.edit_minuta_modal')
        @include('Minutas.Modals.delete_minuta_modal')
        @Push('body')
        <script src="{{ mix('js/minutas.js') }}"></script>
       
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>

          /**Create Modal */
            $(document).ready(function () {
              //$('.create_minuta_modal').on('click', function(){
              //});
              $(document).on('click', '.asistentes', function(event) {

                  if($("#tabla_asistentes > tr").length >= 1) {
                    //$("#ver_tabla" ).show();
                    $("#ver_tabla" ).hide();
                  } else {
                    $("#ver_tabla" ).hide();
                  }

                  agregarAsistente = function agregarAsistente() {
                    //$("#ver_tabla" ).show();
                    $("#ver_tabla" ).hide();
                    var campos_asistentes = $("#campos_asistentes > div").length;
                    var tabla_asistentes = $("#tabla_asistentes > tr").length;
                    var numeroAsistenteCampo = campos_asistentes + 1;
                    var elemento = document.getElementById('asistente' + numeroAsistenteCampo);

                    if(elemento === null) {
                       //console.log('no existe aun ');
                       var numeroAsistente = campos_asistentes + 1;
                    } else {
                      //console.log('ya existe');
                      var numeroAsistenteCampo2 = campos_asistentes + 2;
                      var elemento2 = document.getElementById('asistente' + numeroAsistenteCampo2);
                      if(elemento2 === null) {
                        var numeroAsistente = campos_asistentes + 2;
                      } else {
                        var numeroAsistenteCampo3 = campos_asistentes + 3;
                        var elemento3 = document.getElementById('asistente' + numeroAsistenteCampo3);
                          if(elemento3 === null) {
                            var numeroAsistente = campos_asistentes + 3;
                          } else {
                             var numeroAsistente = campos_asistentes + 4;
                          }
                      }
                    }

                    const crearHtml = new Promise( function(resolve) {

                        $html =
                        '<div class="row" id="' + 'asistente' + numeroAsistente + '">' +
                        '<div class="col-md-5">' +
                        '<div class="form-floating mb-3">' +
                          '<input type="text" class="form-control" placeholder="Nombre" id="' + 'nombre' + numeroAsistente + '"name=" ' + 'nombre' + numeroAsistente + '" required/>' +
                          '<label>' +
                            '<i class="ri-user-line">' + '</i>' + ' Nombre' +
                          '</label>' +
                        "</div>" +
                        "</div>" +
                        '<div class="col-md-5">' +
                        '<div class="form-floating mb-3">' +
                          '<input type="email" class="form-control" placeholder="Correo" id="' + 'correo' + numeroAsistente + '"name=" ' + 'correo' + numeroAsistente + '" required/>' +
                          '<label>' +
                            '<i class="ri-mail-line"></i> Correo' +
                          '</label>' +
                        "</div>" +
                        "</div>" +
                        '<div class="col-md-2 mt-2">' +
                        '<div class="col-md-12">' +
                          '<a class="btn waves-effect waves-light btn-danger"  onclick="borrarAsistente('+ numeroAsistente +', this)">' +
                            '<i class="ri-eraser-line"></i> Borrar' +
                          '</a>' +
                        "</div>" +
                        "</div>" +
                        "</div>";
                        $("#campos_asistentes").append($html);

                        resolve('success');

                    });

                    crearHtml
                        .then( function(resultado) {

                          console.log('resultado');
                          console.log(resultado);

                        })
                        .catch(function(error) {
                          console.log(error);
                        })

                  }

                  borrarAsistente = function borrarAsistente(numero, t) {
                    //Buscamos por id
                    var asistente = document.getElementById ("campos_asistentes");

                    //Borrar campos input del asistente
                    var item = asistente.querySelector('#asistente'+numero);
                    asistente.removeChild(item);
                  }

              });


              $(document).on('click', '.anexos', function(event) {

                  if($("#tabla_anexos > tr").length >= 1) {
                      //$("#ver_tabla_anexos" ).show();
                      $("#ver_tabla_anexos" ).hide();
                    } else {
                      $("#ver_tabla_anexos" ).hide();
                    }

                  agregarAnexo = function agregarAnexo() {
                      //$("#ver_tabla_anexos" ).show();
                      $("#ver_tabla_anexos" ).hide();
                      var campos_anexos = $("#campos_anexos > div").length;
                      var tabla_anexos = $("#tabla_anexos > tr").length;
                      var numeroAnexoCampo = campos_anexos + 1;
                      var elementoAnexo = document.getElementById('rowanexo' + numeroAnexoCampo);

                      if(elementoAnexo === null) {
                        //console.log('no existe aun ');
                        var numeroAnexo = campos_anexos + 1;
                      } else {
                        //console.log('ya existe');
                        var numeroAnexoCampo2 = campos_anexos + 2;
                        var elementoAnexo2 = document.getElementById('rowanexo' + numeroAnexoCampo2);
                        if(elementoAnexo2 === null) {
                          var numeroAnexo = campos_anexos + 2;
                        } else {
                          var numeroAnexoCampo3 = campos_anexos + 3;
                          var elementoAnexo3 = document.getElementById('rowanexo' + numeroAnexoCampo3);
                            if(elementoAnexo3 === null) {
                              var numeroAnexo = campos_anexos + 3;
                            } else {
                              var numeroAnexo = campos_anexos + 4;
                            }
                        }
                      }

                      const crearHtml = new Promise( function(resolve) {

                        $html =
                        '<div class="row" id="' + 'rowanexo' + numeroAnexo + '">' +
                        '<div class="col-md-10">' +
                          '<div class="mb-3" id="' + 'anexo' + numeroAnexo + '">' +
                            '<label for="formFile" class="form-label">' + 'Adjuntar Anexo' + '</label>' +
                            '<input type="file" class="form-control" id="' + 'anexo' + numeroAnexo + '"name=" ' + 'anexo' + numeroAnexo + '" />' +
                          '</div>' +
                        '</div>' +
                        '<div class="col-md-2 mt-4">' +
                          '<div class="col-md-12">' +
                            '<a class="btn waves-effect waves-light btn-danger"  onclick="borrarAnexo('+ numeroAnexo +', this)">' +
                              '<i class="ri-eraser-line"></i> Borrar' +
                            '</a>' +
                          "</div>" +
                        "</div>" +
                        "</div>";
                        $("#campos_anexos").append($html);

                        resolve('success');

                      });

                      crearHtml
                        .then( function(resultado) {

                          console.log('resultado');
                          console.log(resultado);

                        })
                        .catch(function(error) {
                          console.log(error);
                        })
                  }

                  borrarAnexo = function borrarAnexo(numero, t) {
                    //Buscamos por id
                    var anexo = document.getElementById ("campos_anexos");
                    //Borrar campos input del anexo
                    var itemAnexo = anexo.querySelector('#rowanexo'+numero);
                    anexo.removeChild(itemAnexo);
                  }

              });


            });

          /**Edit Modal */
            $(document).ready(function () {

              $('.edit_minuta_modal').on('click', function(){
                var resultData = {};
                var asistentesData = {};
                var anexosData = {};
                var anexoDelete = {};
                var asistenteDelete = {};
                var archivosMinutas = {};

                $('#tabla_minutas_edit').empty();
                $('#campos_asistentes_edit').empty();
                $('#tabla_asistentes_edit').empty();
                $('#campos_anexos_edit').empty();
                $('#tabla_anexos_edit').empty();

                id_minuta = $(this).data('id_minuta');
                $("#id_minuta_edit").val(id_minuta);

                //Sección Datos Generales
                $.ajax({
                url:"show_datos_minuta",
                method:"POST",
                data:{'id_minuta': id_minuta, '_token': _token = $('input[name="_token"]').val()},
                success:function(response){
                        result = JSON.parse(response);
                        resultData = result.data;

                        $('#edit_minuta_modal :input').each(function(){
                            name_edit = $(this).attr("name");
                            if (name_edit != null) {
                                name = name_edit.slice(0,-5);
                                $("#"+name_edit).val(resultData["datos_minuta"][name]);
                            }
                        });

                        //Obtener Id Proyecto selectpicker
                        var proyecto = resultData['datos_minuta']['id_proyecto'];
                        var numProyecto = parseInt(proyecto);
                        //$('#id_proyecto_edit').select2();
                        //$('#id_proyecto_edit').val('2').trigger('change.select2');
                        $('select[name=selValue]').val(numProyecto);
                        $('.selectpicker').selectpicker('refresh');

                        //Obtener fecha de Minuta
                        var fecha = resultData['datos_minuta']['fecha'];
                        var formatFecha = moment(fecha).format('YYYY-MM-DD');
                        $('#fecha_edit').val( formatFecha );
                    }
                });

                //Sección Datos Generales Archivo (minuta)
                $.ajax({
                  url:"show_archivo_minuta",
                  method:"POST",
                  data:{'id_minuta': id_minuta, '_token': _token = $('input[name="_token"]').val()},
                  success:function(response){
                          result = JSON.parse(response);
                          archivosMinutas = result.data;

                          var numeroArchivoEdit = 0;
                          var numeroArchivoEditbr = 1000;
                          archivosMinutas['datos_minutas'].forEach(function(archivo) {
                            ++numeroArchivoEdit;
                            ++numeroArchivoEditbr;

                              $htmlTabla =
                                '<tr id="' + 'archivoTabla_edit' + numeroArchivoEditbr + '">' +
                                '<td id="' + 'td_archivo_edit' + numeroArchivoEditbr + '">' +
                                  '<a class="btn waves-effect waves-light btn-primary text-light font-medium mt-2" href="'+'/storage/minutas/'+archivo.archivo+'">Ver Minuta</a> ' +
                                '</td>' +
                                '<td>' +
                                  '<div class="col-md-12">'+
                                    '<a class="btn waves-effect waves-light btn-danger"  onclick="eliminarArchivoEdit('+ numeroArchivoEditbr +', this, '+ archivo.id_minuta_archivo +')">' +
                                      '<i class="ri-delete-bin-line"></i> Eliminar' +
                                    '</a>' +
                                  '</div>' +
                                '</td>' +
                                "</tr>";
                              $("#tabla_minutas_edit").append($htmlTabla);

                          });

                  }
                });

                eliminarArchivoEdit = function eliminarArchivoEdit(numero, t, id_archivo) {
                  //Eliminar (deleteFlag) de BD
                  $.ajax({
                    url:"eliminar_archivo_minuta",
                    method:"POST",
                    data:{'id_minuta_archivo': id_archivo, '_token': _token = $('input[name="_token"]').val()},
                    success:function(response){
                      result = JSON.parse(response);
                      anexoDelete = result.data;
                      //console.log('anexoDelete');
                      //console.log(anexoDelete);
                    }
                  });
                  //Borrar numero de fila seleccionado
                  var td = t.parentNode;
                  var tr = td.parentNode;
                  var tbody = tr.parentNode;
                  var table = tbody.parentNode;
                  table.removeChild(tbody);
                }

                //Sección Asistentes
                $.ajax({
                  url:"show_datos_asistentes_minuta",
                  method:"POST",
                  data:{'id_minuta': id_minuta, '_token': _token = $('input[name="_token"]').val()},
                  success:function(response){
                          result = JSON.parse(response);
                          asistentesData = result.data;
                          var numeroAsistenteEdit = 0;
                          var numeroAsistenteEditbr = 1000;
                          asistentesData['datos_asistentes'].forEach(function(asistente) {
                            ++numeroAsistenteEdit;
                            ++numeroAsistenteEditbr;

                              $htmlTabla =
                                '<tr id="'+'asistenteTabla_edit' + numeroAsistenteEditbr + '">' +
                                '<td id="'+'td_nombre_edit' + numeroAsistenteEditbr + '">' +
                                  asistente.nombre +
                                '</td>' +
                                '<td id="'+'td_correo_edit' + numeroAsistenteEditbr + '">' +
                                  asistente.correo +
                                '</td>' +
                                '<td>' +
                                  '<div class="col-md-12">'+
                                    '<a class="btn waves-effect waves-light btn-danger"  onclick="eliminarAsistenteEdit('+ numeroAsistenteEditbr +', this, '+ asistente.id_minuta_asistente +')">' +
                                      '<i class="ri-delete-bin-line"></i> Eliminar' +
                                    '</a>' +
                                  '</div>' +
                                '</td>' +
                                "</tr>";
                                $("#tabla_asistentes_edit").append($htmlTabla);

                          });
                  }
                });

                eliminarAsistenteEdit = function eliminarAsistenteEdit(numero, t, id_asistente) {
                  //Eliminar (deleteFlag) de BD
                  $.ajax({
                    url:"eliminar_asistente_minuta",
                    method:"POST",
                    data:{'id_minuta_asistente': id_asistente, '_token': _token = $('input[name="_token"]').val()},
                    success:function(response){
                      result = JSON.parse(response);
                      asistenteDelete = result.data;
                      //console.log('asistenteDelete');
                      //console.log(asistenteDelete);
                    }
                  });
                  //Borrar numero de fila seleccionado
                  var td = t.parentNode;
                  var tr = td.parentNode;
                  var tbody = tr.parentNode;
                  var table = tbody.parentNode;
                  table.removeChild(tbody);
                }


                $(document).on('click', '.asistentes-edit', function(event) {

                  if($("#tabla_asistentes_edit > tr").length >= 1) {
                    $("#ver_tabla" ).show();
                  } else {
                    $("#ver_tabla_edit" ).show();
                  }

                  agregarAsistenteEdit = function agregarAsistenteEdit() {
                    $("#ver_tabla" ).show();
                    var campos_asistentes_edit = $("#campos_asistentes_edit > div").length;
                    var tabla_asistentes_edit = $("#tabla_asistentes_edit > tr").length;
                    var numeroAsistenteCampoEdit = campos_asistentes_edit + 1;
                    var elementoEdit = document.getElementById('asistente_edit' + numeroAsistenteCampoEdit);

                    if(elementoEdit === null) {
                       //console.log('no existe aun ');
                       var numeroAsistenteEdit = campos_asistentes_edit + 1;
                    } else {
                      //console.log('ya existe');
                      var numeroAsistenteCampoEdit2 = campos_asistentes_edit + 2;
                      var elementoEdit2 = document.getElementById('asistente_edit' + numeroAsistenteCampoEdit2);
                      if(elementoEdit2 === null) {
                        var numeroAsistenteEdit = campos_asistentes_edit + 2;
                      } else {
                        var numeroAsistenteCampoEdit3 = campos_asistentes_edit + 3;
                        var elementoEdit3 = document.getElementById('asistente_edit' + numeroAsistenteCampoEdit3);
                          if(elementoEdit3 === null) {
                            var numeroAsistenteEdit = campos_asistentes_edit + 3;
                          } else {
                             var numeroAsistenteEdit = campos_asistentes_edit + 4;
                          }
                      }
                    }

                    const crearHtmlEdit = new Promise( function(resolve) {

                        $html =
                        '<div class="row" id="' + 'asistente_edit' + numeroAsistenteEdit + '">' +
                        '<div class="col-md-5">' +
                        '<div class="form-floating mb-3">' +
                          '<input type="text" class="form-control" placeholder="Nombre" id="'+'nombre_edit' + numeroAsistenteEdit + '"name="'+'nombre_edit' + numeroAsistenteEdit + '" required/>' +
                          '<label>' +
                            '<i class="ri-user-line">' + '</i>' + ' Nombre' +
                          '</label>' +
                        "</div>" +
                        "</div>" +
                        '<div class="col-md-5">' +
                        '<div class="form-floating mb-3">' +
                          '<input type="email" class="form-control" placeholder="Correo" id="'+'correo_edit' + numeroAsistenteEdit + '"name="'+'correo_edit' + numeroAsistenteEdit + '" required/>' +
                          '<label>' +
                            '<i class="ri-mail-line"></i> Correo' +
                          '</label>' +
                        "</div>" +
                        "</div>" +
                        '<div class="col-md-2 mt-2">' +
                        '<div class="col-md-12">' +
                          '<a class="btn waves-effect waves-light btn-danger"  onclick="borrarAsistenteEdit('+ numeroAsistenteEdit +', this)">' +
                            '<i class="ri-eraser-line"></i> Borrar' +
                          '</a>' +
                        "</div>" +
                        "</div>" +
                        "</div>";
                        $("#campos_asistentes_edit").append($html);

                        resolve('success');

                    });

                    crearHtmlEdit
                        .then( function(resultado) {

                          console.log('resultado');
                          console.log(resultado);

                        })
                        .catch(function(error) {
                          console.log(error);
                        })

                  }

                  borrarAsistenteEdit = function borrarAsistenteEdit(numero, t) {
                    //Buscamos por id
                    var asistenteEdit = document.getElementById ("campos_asistentes_edit");
                    var tablaAsistenteEdit = document.getElementById ("tabla_asistentes_edit");
                    //Borrar numero de fila seleccionado
                    var td = t.parentNode;
                    var tr = td.parentNode;
                    var tbody = tr.parentNode;
                    var table = tbody.parentNode;
                    table.removeChild(tbody);
                    //Borrar campos input del asistente
                    var itemEdit = asistenteEdit.querySelector('#asistente_edit'+numero);
                    asistenteEdit.removeChild(itemEdit);
                  }

                });

                //Route::post('show_datos_asistentes_minuta', 'minutas_controller@show_datos_asistentes_minuta');
                //Sección Anexos
                $.ajax({
                  url:"show_datos_anexos_minuta",
                  method:"POST",
                  data:{'id_minuta': id_minuta, '_token': _token = $('input[name="_token"]').val()},
                  success:function(response){
                          result = JSON.parse(response);
                          anexosData = result.data;
                          var numeroAnexoEdit = 0;
                          var numeroAnexoEditbr = 1000;
                          anexosData['datos_anexos'].forEach(function(anexo) {
                            ++numeroAnexoEdit;
                            ++numeroAnexoEditbr;

                              $htmlTabla =
                                '<tr id="' + 'anexoTabla_edit' + numeroAnexoEditbr + '">' +
                                '<td id="' + 'td_anexo_edit' + numeroAnexoEditbr + '">' +
                                  '<a class="btn waves-effect waves-light btn-primary text-light font-medium mt-2" href="'+'/storage/minutas/'+anexo.anexo+'">Ver Documento</a> ' +
                                '</td>' +
                                '<td>' +
                                  '<div class="col-md-12">'+
                                    '<a class="btn waves-effect waves-light btn-danger"  onclick="eliminarAnexoEdit('+ numeroAnexoEditbr +', this, '+ anexo.id_minuta_anexo +')">' +
                                      '<i class="ri-delete-bin-line"></i> Eliminar' +
                                    '</a>' +
                                  '</div>' +
                                '</td>' +
                                "</tr>";
                              $("#tabla_anexos_edit").append($htmlTabla);

                          });
                  }

                });

                eliminarAnexoEdit = function eliminarAnexoEdit(numero, t, id_anexo) {
                  //Eliminar (deleteFlag) de BD
                  $.ajax({
                    url:"eliminar_anexo_minuta",
                    method:"POST",
                    data:{'id_minuta_anexo': id_anexo, '_token': _token = $('input[name="_token"]').val()},
                    success:function(response){
                      result = JSON.parse(response);
                      anexoDelete = result.data;
                      //console.log('anexoDelete');
                      //console.log(anexoDelete);
                    }
                  });
                  //Borrar numero de fila seleccionado
                  var td = t.parentNode;
                  var tr = td.parentNode;
                  var tbody = tr.parentNode;
                  var table = tbody.parentNode;
                  table.removeChild(tbody);
                }

                $(document).on('click', '.anexos-edit', function(event) {

                  if($("#tabla_anexos_edit > tr").length >= 1) {
                    $("#ver_tabla_anexos_edit" ).show();
                  } else {
                    $("#ver_tabla_anexos_edit" ).show();
                  }

                  agregarAnexoEdit = function agregarAnexoEdit() {
                      $("#ver_tabla_anexos" ).show();
                      var campos_anexos_edit = $("#campos_anexos_edit > div").length;
                      var tabla_anexos_edit = $("#tabla_anexos_edit > tr").length;
                      var numeroAnexoCampoEdit = campos_anexos_edit + 1;
                      var elementoAnexoEdit = document.getElementById('rowanexo_edit' + numeroAnexoCampoEdit);

                      if(elementoAnexoEdit === null) {
                        //console.log('no existe aun ');
                        var numeroAnexoEdit = campos_anexos_edit + 1;
                      } else {
                        //console.log('ya existe');
                        var numeroAnexoCampoEdit2 = campos_anexos_edit + 2;
                        var elementoAnexoEdit2 = document.getElementById('rowanexo_edit' + numeroAnexoCampoEdit2);
                        if(elementoAnexoEdit2 === null) {
                          var numeroAnexoEdit = campos_anexos_edit + 2;
                        } else {
                          var numeroAnexoCampoEdit3 = campos_anexos_edit + 3;
                          var elementoAnexoEdit3 = document.getElementById('rowanexo_edit' + numeroAnexoCampoEdit3);
                            if(elementoAnexoEdit3 === null) {
                              var numeroAnexoEdit = campos_anexos_edit + 3;
                            } else {
                              var numeroAnexoEdit = campos_anexos_edit + 4;
                            }
                        }
                      }

                      const crearHtmlAnexoEdit = new Promise( function(resolve) {

                        $html =
                        '<div class="row" id="' + 'rowanexo_edit' + numeroAnexoEdit + '">' +
                        '<div class="col-md-10">' +
                          '<div class="mb-3" id="' + 'anexo_edit' + numeroAnexoEdit + '">' +
                            '<label for="formFile" class="form-label">' + 'Adjuntar Anexo' + '</label>' +
                            '<input type="file" class="form-control" id="' + 'anexo_edit' + numeroAnexoEdit + '"name="'+'anexo_edit' + numeroAnexoEdit + '" />' +
                          '</div>' +
                        '</div>' +
                        '<div class="col-md-2 mt-4">' +
                          '<div class="col-md-12">' +
                            '<a class="btn waves-effect waves-light btn-danger"  onclick="borrarAnexoEdit('+ numeroAnexoEdit +', this)">' +
                              '<i class="ri-eraser-line"></i> Borrar' +
                            '</a>' +
                          "</div>" +
                        "</div>" +
                        "</div>";
                        $("#campos_anexos_edit").append($html);

                        resolve('success');

                      });

                      crearHtmlAnexoEdit
                        .then( function(resultado) {

                          console.log('resultado');
                          console.log(resultado);

                        })
                        .catch(function(error) {
                          console.log(error);
                        })
                    }

                    borrarAnexoEdit = function borrarAnexoEdit(numero, t) {
                      //Buscamos por id
                      var anexoEdit = document.getElementById ("campos_anexos_edit");
                      var tablaAnexoEdit = document.getElementById ("tabla_anexos_edit");
                      //Borrar numero de fila seleccionado
                      var td = t.parentNode;
                      var tr = td.parentNode;
                      var tbody = tr.parentNode;
                      var table = tbody.parentNode;
                      table.removeChild(tbody);
                      //Borrar campos input del anexo
                      var itemAnexoEdit = anexoEdit.querySelector('#rowanexo_edit'+numero);
                      anexoEdit.removeChild(itemAnexoEdit);
                    }

                });


              });


            });

          /**Delete modal */
            $(document).ready(function () {

                $('.delete_minuta_modal').on('click', function(){
                  id_minuta = $(this).data('id_minuta');
                  var resultData = {};
                  $("#delete_id_minuta").val(id_minuta);

                  $.ajax({
                    url:"show_datos_minuta",
                    method:"POST",
                    data:{'id_minuta': id_minuta, '_token': _token = $('input[name="_token"]').val()},
                    success:function(response){
                        result = JSON.parse(response);
                        resultData = result.data;

                        nombre_completo = resultData["datos_minuta"]["numero"];
                        html = "<h3 style='color: blue'>Minuta No "+nombre_completo+"</h3>";
                        $("#delete_minuta").html(html);
                    }
                  });

                });

            });



        </script>

        <script>
          $(document).on('click', '.enviar_correo_modal', function(event) {
            id_minuta = $(this).data('id_minuta');
            $('#id_minuta_email').val(id_minuta);
          });
        </script>
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
          
        @endpush
@endsection
