<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;
use App\Models\Supervisor;
use App\User;
use App\Models\Tipo_Usuario;
use App\Models\Usuario;
use App\Models\Modulo;
use App\Models\Modulo_x_Usuario;
use App\Models\Proyecto_x_Usuario;
use App\Models\Proyecto_x_Beneficiario;
use App\Models\Proyectos_estatu;

use Illuminate\Http\Request;
use App\Models\Papelera;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Auth;
use App\Traits\Obtener_proyectos_status;
use App\Models\Tarea;
use App\Models\Tarea_Presupuesto;
use App\Models\Estimacion;
use App\Models\Estimacion_Concepto;

class proyectos_controller extends Controller
{
    public function index()
    {
        //Proyectos por usuario
        $proyectos_usuario = Proyecto_x_Usuario::select('id_proyecto')
        ->where('idusuarios',Auth::user()->id)
        ->where('activo',1)
        ->get();

        //Obtener Proyectos de la BD
        $proyectos = Proyecto::join("proyectos_estatus","proyectos_estatus.id_proyecto_estatus", "=", "proyectos.estatus_proyecto")
        ->join("proyectos_tipo_obras","proyectos_tipo_obras.id_proyecto_tipo_obra", "=", "proyectos.tipo_obra_proyecto")
        ->join("proyectos_modalidad_obras","proyectos_modalidad_obras.id_proyecto_modalidad_obra", "=", "proyectos.modalidad_obra_proyecto")
        ->join("proyectos_sector_obras","proyectos_sector_obras.id_proyecto_sector_obra", "=", "proyectos.sector_obra_proyecto")
        ->join("estados","estados.id_estado", "=", "proyectos.id_estado")
        ->join("contratistas","contratistas.idcontratistas", "=", "proyectos.contratista_proyecto")
        ->select('proyectos.id_proyecto', 'proyectos.folio_proyecto', 'proyectos.nombre_proyecto', 'nombre_proyecto_estatus', 'nombre_proyecto_tipo_obra', 'nombre_proyecto_modalidad_obra', 'nombre_proyecto_sector_obra', 'nombre_estado',
        'proyectos.municipio_proyecto', 'nombre', 'proyectos.fecha_inicio_proyecto', 'proyectos.fecha_finalizacion_proyecto', 'proyectos.lat_proyecto', 'proyectos.lng_proyecto', 'proyectos.delete_flag')
        ->whereIn('proyectos.id_proyecto',$proyectos_usuario)
        ->where('proyectos.delete_flag', 0)
        ->get();

        //Permisos por modulo
        $permisos = (new modulos_controller)->permisos_por_modulo(
            url()->current(),
            Auth::user()->id
        );

        $usuarios = Usuario::select(
            'idusuarios',
            'nombre',
            'apaterno',
            'amaterno',
            'usuario',
            'estatus'
        )
        ->where('delete_flag',0)
        ->get();

        $modulos_permisos = Modulo::select(
            'idmodulos',
            'nombre'
        )
        ->orderBy('idmodulos','ASC')
        ->get();

        $tipo_usuarios = Tipo_Usuario::select(
            'id_tipo_usuario',
            'tipo_usuario'
        )
        ->get();

        $supervisores = Supervisor::select('*')->where('delete_flag',0)->get();

        //Obtener datos para la creación de una nuevo proyecto
        $proyectos_estatus = DB::table('proyectos_estatus')->get()->pluck('id_proyecto_estatus', 'nombre_proyecto_estatus');
        $proyectos_tipo_obras = DB::table('proyectos_tipo_obras')->get()->pluck('id_proyecto_tipo_obra', 'nombre_proyecto_tipo_obra');
        $proyectos_modalidad_obras = DB::table('proyectos_modalidad_obras')->get()->pluck('id_proyecto_modalidad_obra', 'nombre_proyecto_modalidad_obra');
        $proyectos_sector_obras = DB::table('proyectos_sector_obras')->get()->pluck('id_proyecto_sector_obra', 'nombre_proyecto_sector_obra');
        $estados = DB::table('estados')->get()->pluck('id_estado', 'nombre_estado');
        $contratistas = DB::table('contratistas')->get()->pluck('idcontratistas', 'nombre');

        return view('Proyectos.index')
        ->with('usuarios',$usuarios)
        ->with('modulos_permisos',$modulos_permisos)
        ->with('tipo_usuarios',$tipo_usuarios)
        ->with('permisos',$permisos)
        ->with('proyectos',$proyectos)
        ->with('proyectos_estatus',$proyectos_estatus)
        ->with('proyectos_tipo_obras',$proyectos_tipo_obras)
        ->with('proyectos_modalidad_obras',$proyectos_modalidad_obras)
        ->with('proyectos_sector_obras',$proyectos_sector_obras)
        ->with('estados',$estados)
        ->with('contratistas',$contratistas)
        ->with('supervisores',$supervisores)
        ->with('status', Obtener_proyectos_status::calcular_status());
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
        //dd($request->all());

        
        $data = $request->validate([
            'folio_proyecto' => 'required',
            'nombre_proyecto' => 'required'
        ]);
        
        /*
        $data = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);
 
        if ($data->fails()) {
            return redirect('/administracion-de-proyectos')
                        ->withErrors($data)
                        ->withInput();
        }
        */




        $proyectocreado = $request->folio_proyecto;
        //$inversion = $request->inversion_programada;
        //$inversionfloat = (float) $inversion;

        /*$proyecto = new Proyecto();
        $proyecto->folio_proyecto = $request->folio_proyecto;
        $proyecto->nombre_proyecto = $request->nombre_proyecto;
        $proyecto->estatus_proyecto = $request->estatus_proyecto;
        $proyecto->tipo_obra_proyecto = $request->tipo_obra_proyecto;
        $proyecto->modalidad_obra_proyecto = $request->modalidad_obra_proyecto;
        $proyecto->sector_obra_proyecto = $request->sector_obra_proyecto;
        $proyecto->id_estado = $request->estado_proyecto;
        $proyecto->municipio_proyecto = $request->municipio_proyecto;
        $proyecto->contratista_proyecto = $request->contratista_proyecto;
        $proyecto->fecha_inicio_proyecto = $request->fecha_inicio_proyecto;
        $proyecto->fecha_finalizacion_proyecto = $request->fecha_finalizacion_proyecto;
        $proyecto->direccion_proyecto = $request->direccion_proyecto;
        $proyecto->lat_proyecto = $request->lat_proyecto;
        $proyecto->lng_proyecto = $request->lng_proyecto;
        $proyecto->save();*/

        if( isset($request->id_supervisor) ){

            $id_supervisor = $request->id_supervisor;

        } else {

            $searchString = " ";
            $replaceString = "";
            $originalString = $request->phone; 
            $phone_string = str_replace($searchString, $replaceString, $originalString); 
            $whats = '+'.$request->codigo.$phone_string;
    
            if( $request['imagen_supervisor'] ) {
                // obteber la ruta de la imagen
                $ruta_imagen = $request['imagen_supervisor']->store('imagen-supervisores', 'public');
              } else {
                $ruta_imagen = 'logotipos-contratistas/no-image.png';
              }
            
            $datos_supervisor = Supervisor::create([
                'empresa' =>$request->empresa_supervisor,
                'representante' =>$request->representante_supervisor,
                'rfc' =>$request->rfc_supervisor,
                'phone' =>$whats,
                'telefono' =>$request->telefono_supervisor,
                'correo' =>$request->correo_supervisor,
                'pais' =>$request->pais_supervisor,
                'estado' =>$request->estado_supervisor,
                'municipio' =>$request->municipio_supervisor,
                'direccion' =>$request->direccion_supervisor,
                'imagen_supervisor' =>$ruta_imagen
            ]);
            $id_supervisor = $datos_supervisor->id_supervisor;

        }

        $datos_proyecto = Proyecto::create([
            'folio_proyecto'=>$data['folio_proyecto'],
            'codigo_transparencia'=>Str::random(8),
            'nombre_proyecto'=>$data['nombre_proyecto'],
            'estatus_proyecto'=>$request->estatus_proyecto,
            'tipo_obra_proyecto'=>$request->tipo_obra_proyecto,
            'modalidad_obra_proyecto'=>$request->modalidad_obra_proyecto,
            'sector_obra_proyecto'=>$request->sector_obra_proyecto,
            'id_estado'=>$request->estado_proyecto,
            'municipio_proyecto'=>$request->municipio_proyecto,
            'contratista_proyecto'=>$request->contratista_proyecto,
            'fecha_inicio_proyecto'=>$request->fecha_inicio_proyecto,
            'fecha_finalizacion_proyecto'=>$request->fecha_finalizacion_proyecto,
            'direccion_proyecto'=>$request->direccion_proyecto,
            'lat_proyecto'=>$request->lat_proyecto,
            'lng_proyecto'=>$request->lng_proyecto,
            //'inversion_programada'=>$inversionfloat,
            //'total_estimaciones'=>$request->estimaciones_programadas,
            //'total_supervisiones'=>$request->supervisiones_programadas,
            'total_ben_dir_mujeres'=>$request->ben_directos_mujeres,
            'total_ben_dir_hombres'=>$request->ben_directos_hombres,
            'total_ben_ind_mujeres'=>$request->ben_indirectos_mujeres,
            'total_ben_ind_hombres'=>$request->ben_indirectos_hombres,
            'id_supervisor'=>$id_supervisor
        ]);

        $mensaje = 'Proyecto '.$proyectocreado.' creado exitosamente';

        (new notificaciones_controller)->nueva_notificacion($mensaje,13,1,Auth::user()->id);
        $this->relaciona_proyecto_usuario($datos_proyecto->id_proyecto,Auth::user()->id);

        return Redirect::back()->with('success','¡Registro creado exitosamente!');

    }

    public function update(Request $request)
    {
        //
        //dd($request->all());
        $folio_actualizado = $request->folio_proyecto_edit;
        $datos_proyecto = Proyecto::select('*')->where('id_proyecto',$request->id_proyecto_edit)->first();

        if( isset($request->id_supervisor_select_edit) ){

            $id_supervisor = $request->id_supervisor_select_edit;

        } else {

            $searchString = " ";
            $replaceString = "";
            $originalString = $request->phone_edit; 
            $phone_string = str_replace($searchString, $replaceString, $originalString); 
            $whats = '+'.$request->codigo_edit.$phone_string;
    
            if( $request['imagen_supervisor_nueva'] ) {
                // obteber la ruta de la imagen
                $ruta_imagen = $request['imagen_supervisor_nueva']->store('imagen-supervisores', 'public');
              } else {
                $ruta_imagen = $request->imagen_supervisor_actual;
              }

            $id_supervisor = $request->id_supervisor_edit;

            Supervisor::where('id_supervisor',$id_supervisor)->update([
                'empresa' =>$request->empresa_supervisor_edit,
                'representante' =>$request->representante_supervisor_edit,
                'rfc' =>$request->rfc_supervisor_edit,
                'phone' =>$whats,
                'telefono' =>$request->telefono_supervisor_edit,
                'correo' =>$request->correo_supervisor_edit,
                'pais' =>$request->pais_supervisor_edit,
                'estado' =>$request->estado_supervisor_edit,
                'municipio' =>$request->municipio_supervisor_edit,
                'direccion' =>$request->direccion_supervisor_edit,
                'imagen_supervisor' =>$ruta_imagen
            ]);
            

        }

        Proyecto::where('id_proyecto',$request->id_proyecto_edit)->update([
            'folio_proyecto'=>$request->folio_proyecto_edit,
            'nombre_proyecto'=>$request->nombre_proyecto_edit,
            'estatus_proyecto'=>$request->estatus_proyecto_edit,
            'tipo_obra_proyecto'=>$request->tipo_obra_proyecto_edit,
            'modalidad_obra_proyecto'=>$request->modalidad_obra_proyecto_edit,
            'sector_obra_proyecto'=>$request->sector_obra_proyecto_edit,
            'id_estado'=>$request->id_estado_edit,
            'municipio_proyecto'=>$request->municipio_proyecto_edit,
            'contratista_proyecto'=>$request->contratista_proyecto_edit,
            'fecha_inicio_proyecto'=>$request->fecha_inicio_proyecto_edit,
            'fecha_finalizacion_proyecto'=>$request->fecha_finalizacion_proyecto_edit,
            'direccion_proyecto'=>$request->direccion_proyecto_edit,
            'lat_proyecto'=>$request->lat_proyecto_edit,
            'lng_proyecto'=>$request->lng_proyecto_edit,
            'id_supervisor'=>$id_supervisor
        ]);

        $mensaje = 'Projecto '.$folio_actualizado.' actualizado exitosamente';

        (new notificaciones_controller)->nueva_notificacion($mensaje,13,2,Auth::user()->id);

        return Redirect::back()->with('success','¡Registro actualizado exitosamente!');

    }


    public function delete(Request $request)
    {
        //
        $datos_proyecto = Proyecto::select('*')->where('id_proyecto',$request->delete_id_proyecto)->first();

        $descripcion = "Proyecto Eliminado";
        $detalles = $datos_proyecto->nombre_proyecto;
        $modulo = "Proyectos";
        $tabla_origen = "proyectos";
        $pk_origen = "id_proyecto";
        $id_origen = $request->delete_id_proyecto;
        $mensaje = 'Projecto '.$detalles.' eliminado';

        (new papelera_controller)->crear_registro($descripcion,$detalles,$modulo,$tabla_origen,$pk_origen,$id_origen,Auth::user()->id);

        Proyecto::where('id_proyecto', $request->delete_id_proyecto)->update([
          'delete_flag'=>1
        ]);

        (new notificaciones_controller)->nueva_notificacion($mensaje,13,3,Auth::user()->id);
        return Redirect::back()->with('success',"¡Registro eliminado exitosamente!");

    }

    public function show_datos_proyecto(Request $request){

        $proyecto = Proyecto::where('id_proyecto', $request->id_proyecto)->first();

        $datos_proyecto = array('datos_proyecto' => $proyecto);
        $datos = json_encode($datos_proyecto);

        echo '{"success": true, "data": '.$datos.'}';
    }


    public function ver_datos_proyecto(Request $request){

        $datos_proyecto = Proyecto::join("proyectos_estatus","proyectos_estatus.id_proyecto_estatus", "=", "proyectos.estatus_proyecto")
        ->join("proyectos_tipo_obras","proyectos_tipo_obras.id_proyecto_tipo_obra", "=", "proyectos.tipo_obra_proyecto")
        ->join("proyectos_modalidad_obras","proyectos_modalidad_obras.id_proyecto_modalidad_obra", "=", "proyectos.modalidad_obra_proyecto")
        ->join("proyectos_sector_obras","proyectos_sector_obras.id_proyecto_sector_obra", "=", "proyectos.sector_obra_proyecto")
        ->join("estados","estados.id_estado", "=", "proyectos.id_estado")
        ->join("contratistas","contratistas.idcontratistas", "=", "proyectos.contratista_proyecto")
        ->select('proyectos.id_proyecto', 'proyectos.id_supervisor', 'proyectos.folio_proyecto', 'proyectos.nombre_proyecto', 'nombre_proyecto_estatus', 'nombre_proyecto_tipo_obra', 'nombre_proyecto_modalidad_obra', 'nombre_proyecto_sector_obra', 'nombre_estado',
                 'proyectos.municipio_proyecto', 'nombre', 'representante', 'rfc', 'telefono', 'correo', 'pais', 'estado', 'municipio', 'direccion', 'imagen_contratista', 'proyectos.fecha_inicio_proyecto', 'proyectos.fecha_finalizacion_proyecto', 'proyectos.lat_proyecto', 'proyectos.lng_proyecto', 'proyectos.direccion_proyecto',
                 'proyectos.inversion_programada', 'proyectos.total_estimaciones', 'proyectos.total_supervisiones', 'proyectos.total_ben_dir_mujeres', 'proyectos.total_ben_dir_hombres', 'proyectos.total_ben_ind_mujeres', 'proyectos.total_ben_ind_hombres', 'proyectos.delete_flag')
        ->where('proyectos.id_proyecto', $request->id_proyecto)
        ->get();

        $datos = json_encode($datos_proyecto);
        echo '{"success": true, "data": '.$datos.'}';

    }

    public function relaciona_proyecto_usuario($id_proyecto,$id_usuario){

        $usuarios = Usuario::all();

        foreach ($usuarios as $key => $value) {
            $activo = 0;
            if ($value->idusuarios == $id_usuario) {
                $activo = 1;
            }

            Proyecto_x_Usuario::create([
                'activo'=>$activo,
                'idusuarios'=>$value->idusuarios,
                'id_proyecto'=>$id_proyecto
            ]);
        }
    }

    public function relaciona_usuario_proyecto($id_usuario){

        $proyectos = Proyecto::all();

        foreach ($proyectos as $key => $value) {

            Proyecto_x_Usuario::create([
                'activo'=>0,
                'idusuarios'=>$id_usuario,
                'id_proyecto'=>$value->id_proyecto
            ]);
        }
    }


    public function proyectos_alertas(Request $request)
    {
        //
        $folio_proyecto = Proyecto::select('folio_proyecto')->where('id_proyecto', $request->id_proyecto)->get();
        $folio = $folio_proyecto[0]['folio_proyecto'];

        if( $request->aseveracionInversion == "Inversion programada excedida" && $request->aseveracionTiempo == "Tiempo programado excedido") {
            $detallesInv = 'El proyecto: '.$folio.' excedió la inversion programada';
            $detallesTie = 'El proyecto: '.$folio.' excedió el tiempo programado';
            $respuesta = 'ambos';
            $datos = json_encode($respuesta);
            (new notificaciones_controller)->nueva_notificacion($detallesInv,13,6,Auth::user()->id);
            (new notificaciones_controller)->nueva_notificacion($detallesTie,13,6,Auth::user()->id);
        } elseif ( $request->aseveracionInversion == "Inversion programada excedida" ) {
            $detallesInv = 'El proyecto: '.$folio.' excedió la inversion programada';
            $respuesta = 'inversion';
            $datos = json_encode($respuesta);
            (new notificaciones_controller)->nueva_notificacion($detallesInv,13,6,Auth::user()->id);
        } elseif ( $request->aseveracionTiempo == "Tiempo programado excedido" ) {
            $detallesTie = 'El proyecto: '.$folio.' excedió el tiempo programado';
            $respuesta = 'tiempo';
            $datos = json_encode($respuesta);
            (new notificaciones_controller)->nueva_notificacion($detallesTie,13,6,Auth::user()->id);
        }

        echo '{"success": true, "data": '.$datos.'}';
        /*
        $folio_proyecto = Proyecto::select('folio_proyecto')->where('id_proyecto', $request->id_proyecto)->get();
        $folio = $folio_proyecto[0]['folio_proyecto'];
        $detalles = 'El proyecto: '.$folio.' excedió la Inversion Programada';
        $datos = json_encode($folio_proyecto);
        (new notificaciones_controller)->nueva_notificacion($detalles,13,6,Auth::user()->id);
        echo '{"success": true, "data": '.$datos.'}';
        */

    }

    public function agregar_beneficiario(Request $request) {
        //dd($request->all());

        if( $request->ben_directos_mujeres != "0" ) {
            $cantidad_beneficiarios = $request->ben_directos_mujeres;
            $cantidad_beneficiarios_int = (int) $cantidad_beneficiarios;
            $cantidad_beneficiarios_inicial = Proyecto::select('total_ben_dir_mujeres')->where('id_proyecto',$request->id_proyecto_estadisticas)->first();
            $cantidad_beneficiarios_inicial_int = (int) $cantidad_beneficiarios_inicial['total_ben_dir_mujeres'];
            $total_ben_dir_mujeres = $cantidad_beneficiarios_int + $cantidad_beneficiarios_inicial_int;
            Proyecto::where('id_proyecto',$request->id_proyecto_estadisticas)->update([
                'total_ben_dir_mujeres'=>$total_ben_dir_mujeres
            ]);
         } else {
             if( $request->ben_directos_hombres != "0" ) {
                $cantidad_beneficiarios = $request->ben_directos_hombres;
                $cantidad_beneficiarios_int = (int) $cantidad_beneficiarios;
                $cantidad_beneficiarios_inicial = Proyecto::select('total_ben_dir_hombres')->where('id_proyecto',$request->id_proyecto_estadisticas)->first();
                $cantidad_beneficiarios_inicial_int = (int) $cantidad_beneficiarios_inicial['total_ben_dir_hombres'];
                $total_ben_dir_hombres = $cantidad_beneficiarios_int + $cantidad_beneficiarios_inicial_int;
                Proyecto::where('id_proyecto',$request->id_proyecto_estadisticas)->update([
                    'total_ben_dir_hombres'=>$total_ben_dir_hombres
                ]);
                } else {
                    if( $request->ben_indirectos_mujeres != "0" ) {
                        $cantidad_beneficiarios = $request->ben_indirectos_mujeres;
                        $cantidad_beneficiarios_int = (int) $cantidad_beneficiarios;
                        $cantidad_beneficiarios_inicial = Proyecto::select('total_ben_ind_mujeres')->where('id_proyecto',$request->id_proyecto_estadisticas)->first();
                        $cantidad_beneficiarios_inicial_int = (int) $cantidad_beneficiarios_inicial['total_ben_ind_mujeres'];
                        $total_ben_ind_mujeres = $cantidad_beneficiarios_int + $cantidad_beneficiarios_inicial_int;
                        Proyecto::where('id_proyecto',$request->id_proyecto_estadisticas)->update([
                            'total_ben_ind_mujeres'=>$total_ben_ind_mujeres
                        ]);
                        }  else {
                            if( $request->ben_indirectos_hombres != "0" ) {
                                $cantidad_beneficiarios = $request->ben_indirectos_hombres;
                                $cantidad_beneficiarios_int = (int) $cantidad_beneficiarios;
                                $cantidad_beneficiarios_inicial = Proyecto::select('total_ben_ind_hombres')->where('id_proyecto',$request->id_proyecto_estadisticas)->first();
                                $cantidad_beneficiarios_inicial_int = (int) $cantidad_beneficiarios_inicial['total_ben_ind_hombres'];
                                $total_ben_ind_hombres = $cantidad_beneficiarios_int + $cantidad_beneficiarios_inicial_int;
                                Proyecto::where('id_proyecto',$request->id_proyecto_estadisticas)->update([
                                    'total_ben_ind_hombres'=>$total_ben_ind_hombres
                                ]);
                                } else {
                                    if( $request->ben_directos_mujeres == "0" && $request->ben_directos_hombres == "0"
                                            && $request->ben_indirectos_mujeres == "0" && $request->ben_indirectos_hombres == "0" ) {
                                                $cantidad_beneficiarios = 'Sin cantidad';
                                        }
                                }
                        }
                }
            }

        if( $cantidad_beneficiarios == 'Sin cantidad' ) {
            return Redirect::back()->with('error','¡Cantidad No valida!');
        } else {
            $proyecto_id = $request->id_proyecto_estadisticas;
            $proyecto_beneficiario = Proyecto_x_Beneficiario::create([
                'cantidad_beneficiarios'=>$cantidad_beneficiarios,
                'idusuarios'=>$request->id_usuario,
                'id_proyecto'=>$request->id_proyecto_estadisticas,
                'id_tipo_beneficiario'=>$request->id_tipo_beneficiario
            ]);
            $mensaje = 'Beneficiario agregado en proyecto id: '.$proyecto_id;
            (new notificaciones_controller)->nueva_notificacion($mensaje,13,2,Auth::user()->id);
            return Redirect::back()->with('success','Beneficiarios actualizado exitosamente!');
        }

    }

    public function editar_inversion_inicial(Request $request) {
        //dd($request->all());
        $proyecto_id = $request->id_proyecto_inversion;
        Proyecto::where('id_proyecto',$request->id_proyecto_inversion)->update([
            'inversion_programada'=>$request->inversion_programada
        ]);
        $mensaje = 'Inversión actualizada en proyecto id: '.$proyecto_id;
        (new notificaciones_controller)->nueva_notificacion($mensaje,13,2,Auth::user()->id);
        return Redirect::back()->with('success','¡Inversión actualizada exitosamente!');
    }

    public function editar_estimaciones_programadas(Request $request) {
        //dd($request->all());
        $proyecto_id = $request->id_proyecto_estimaciones;
        Proyecto::where('id_proyecto',$request->id_proyecto_estimaciones)->update([
            'total_estimaciones'=>$request->estimaciones_programadas
        ]);
        $mensaje = 'Total de estimaciones actualizada en proyecto id: '.$proyecto_id;
        (new notificaciones_controller)->nueva_notificacion($mensaje,13,2,Auth::user()->id);
        return Redirect::back()->with('success','¡Total Estimaciones actualizado!');
    }

    public function editar_supervisiones_programadas(Request $request) {
        //dd($request->all());
        $proyecto_id = $request->id_proyecto_supervisiones;
        Proyecto::where('id_proyecto',$request->id_proyecto_supervisiones)->update([
            'total_supervisiones'=>$request->supervisiones_programadas
        ]);
        $mensaje = 'Total de supervisiones actualizada en proyecto id: '.$proyecto_id;
        (new notificaciones_controller)->nueva_notificacion($mensaje,13,2,Auth::user()->id);
        return Redirect::back()->with('success','¡Total Supervisiones actualizado!');
    }


    public function obtener_presupuesto_proyecto(Request $request) {
        //$datos = json_encode( $request->all() );
        //echo '{"success": true, "data": '.$datos.'}';
       
        //Obtener solo un id_tarea_presupuesto y id_proyectos si hay 
        $importes_proyecto = Tarea_Presupuesto::join("tareas","tareas.id_tarea", "=", "tarea_presupuestos.id_tarea")
        ->select('tarea_presupuestos.importe', 'id_proyecto')
        ->where('id_proyecto', $request->id_proyecto)
        ->where('tarea_presupuestos.id_tipo_concepto_presupuesto', 1)
        ->get();

        $importe_ordinario = 0;
        foreach($importes_proyecto as $importe) {   
            $importe_concepto = (float)$importe['importe'];
            $importe_ordinario+=$importe_concepto;
        }

        Proyecto::where('id_proyecto',$request->id_proyecto)->update([
            'inversion_programada'=>$importe_ordinario
        ]);


        //Obtener importes extraordinarios 
        $importes_extraordinario = Tarea_Presupuesto::join("tareas","tareas.id_tarea", "=", "tarea_presupuestos.id_tarea")
        ->select('tarea_presupuestos.importe', 'id_proyecto')
        ->where('id_proyecto', $request->id_proyecto)
        ->where('tarea_presupuestos.id_tipo_concepto_presupuesto', 2)
        ->get();

        $importe_total_extraordinario = 0;
        foreach($importes_extraordinario as $importe_extraordinario) {   
            $importe_concepto_extra = (float)$importe_extraordinario['importe'];
            $importe_total_extraordinario+=$importe_concepto_extra;
        }


        $array_importes = array($importe_ordinario, $importe_total_extraordinario);

        $datos = json_encode( $array_importes );
        echo '{"success": true, "data": '.$datos.'}';

        //$datos = json_encode( $importe_ordinario );
        //echo '{"success": true, "data": '.$datos.'}';

    }

    /*
    public function avance_fisico_proyecto(Request $request) {

        $estimacion_conceptos = Estimacion_Concepto::join("tarea_presupuestos","tarea_presupuestos.id_tarea_presupuesto", "=", "estimacion_conceptos.id_tarea_presupuesto")
        ->join("estimaciones","estimaciones.id_estimacion", "=", "estimacion_conceptos.id_estimacion")
        ->select('estimacion_conceptos.cantidad', 'estimacion_conceptos.id_tarea_presupuesto', 'estimacion_conceptos.estatus AS estatus_aprobacion', 'id_proyecto', 'estimaciones.estatus AS estatus_pago', 'codigo', 'precio_unitario')
        ->where('id_proyecto',$request->id_proyecto)
        ->get();

        $avance_fisico_proyecto = 0;
        foreach($estimacion_conceptos as $estimacion) {   
            $cantidad = (float)$estimacion['cantidad'];
            $precio_unitario = (float)$estimacion['precio_unitario'];
            $importe_estimacion = $cantidad * $precio_unitario;
            $avance_fisico_proyecto+=$importe_estimacion;
        }

        $avance_fisico = round($avance_fisico_proyecto, 2);

        $datos = json_encode($avance_fisico);
        echo '{"success": true, "data": '.$datos.'}';
    }
    */

    public function avances_proyecto(Request $request) {

        $estimacion_conceptos_fisico = Estimacion_Concepto::join("tarea_presupuestos","tarea_presupuestos.id_tarea_presupuesto", "=", "estimacion_conceptos.id_tarea_presupuesto")
        ->join("estimaciones","estimaciones.id_estimacion", "=", "estimacion_conceptos.id_estimacion")
        ->select('estimacion_conceptos.cantidad', 'estimacion_conceptos.id_tarea_presupuesto', 'estimacion_conceptos.estatus AS estatus_aprobacion', 'id_proyecto', 'estimaciones.estatus AS estatus_pago', 'codigo', 'precio_unitario')
        ->where('id_proyecto',$request->id_proyecto)
        ->get();

        $avance_fisico_proyecto = 0;
        foreach($estimacion_conceptos_fisico as $estimacion_fisico) {   
            $cantidad = (float)$estimacion_fisico['cantidad'];
            $precio_unitario = (float)$estimacion_fisico['precio_unitario'];
            $importe_estimacion = $cantidad * $precio_unitario;
            $avance_fisico_proyecto+=$importe_estimacion;
        }
        $avance_fisico = round($avance_fisico_proyecto, 2);

        $estimacion_conceptos_financiero = Estimacion_Concepto::join("tarea_presupuestos","tarea_presupuestos.id_tarea_presupuesto", "=", "estimacion_conceptos.id_tarea_presupuesto")
        ->join("estimaciones","estimaciones.id_estimacion", "=", "estimacion_conceptos.id_estimacion")
        ->select('estimacion_conceptos.cantidad', 'estimacion_conceptos.id_tarea_presupuesto', 'estimacion_conceptos.estatus AS estatus_aprobacion', 'id_proyecto', 'estimaciones.estatus AS estatus_pago', 'codigo', 'precio_unitario')
        ->where('id_proyecto',$request->id_proyecto)
        ->where('estimaciones.estatus','Pagado')
        ->get();

        $avance_financiero_proyecto = 0;
        foreach($estimacion_conceptos_financiero as $estimacion_financiero) {   
            $cantidad = (float)$estimacion_financiero['cantidad'];
            $precio_unitario = (float)$estimacion_financiero['precio_unitario'];
            $importe_estimacion = $cantidad * $precio_unitario;
            $avance_financiero_proyecto+=$importe_estimacion;
        }
        $avance_financiero = round($avance_financiero_proyecto, 2);

        $avance_proyecto = array($avance_fisico, $avance_financiero);
        $datos = json_encode($avance_proyecto);
        echo '{"success": true, "data": '.$datos.'}';
        
    }

    public function total_estimaciones_proyecto(Request $request) {

        $estimaciones_proyecto = Estimacion::where('id_proyecto', $request->id_proyecto)->get();
        $total_estimaciones =  $estimaciones_proyecto->count();

        $conceptos_x_estimacion = Estimacion::join("estimacion_conceptos","estimacion_conceptos.id_estimacion", "=", "estimaciones.id_estimacion")
        ->where('id_proyecto', $request->id_proyecto)
        ->selectRaw('count(estimacion_conceptos.id_estimacion) as Conceptos')
        ->groupBy('estimacion_conceptos.id_estimacion')
        ->get();

        $cantidad_conceptos = array();
        foreach($conceptos_x_estimacion as $conceptos) {
            array_push($cantidad_conceptos, $conceptos['Conceptos']);
        }

        $estimacion_conceptos = Estimacion_Concepto::join("tarea_presupuestos","tarea_presupuestos.id_tarea_presupuesto", "=", "estimacion_conceptos.id_tarea_presupuesto")
        ->join("estimaciones","estimaciones.id_estimacion", "=", "estimacion_conceptos.id_estimacion")
        ->select('estimacion_conceptos.cantidad', 'estimacion_conceptos.id_tarea_presupuesto', 'estimacion_conceptos.estatus AS estatus_aprobacion', 'id_proyecto', 'estimaciones.estatus AS estatus_pago', 'codigo', 'precio_unitario')
        ->where('id_proyecto',$request->id_proyecto)
        ->get();
        $importe_total = 0;
        foreach($estimacion_conceptos as $estimacion) {   
            $cantidad = (float)$estimacion['cantidad'];
            $precio_unitario = (float)$estimacion['precio_unitario'];
            $importe_estimacion = $cantidad * $precio_unitario;
            $importe_total+=$importe_estimacion;
        }
        $importe_total_estimacion = round($importe_total, 2);


        $array_estimaciones = array($total_estimaciones, $cantidad_conceptos, $importe_total_estimacion);

        $datos = json_encode( $array_estimaciones );
        echo '{"success": true, "data": '.$datos.'}';
    }


    public function show_datos_supervisor(Request $request){

        $supervisor = Supervisor::where('id_supervisor', $request->id_supervisor)->first();

        $datos_supervisor = array('datos_supervisor' => $supervisor);
        $datos = json_encode($datos_supervisor);

        echo '{"success": true, "data": '.$datos.'}';
    }



}
