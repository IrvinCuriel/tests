<?php

namespace App\Http\Controllers;

use App\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ArchivoController extends Controller
{

    public function index()
    {
        //
        return view('archivos.index');
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
        //dd($request->all());

        /*
        $documento = $request->file('file');
        //return $documento->extension();
        //return $documento;
        $nombreDocumento = time() . '.' . $documento->extension();
        //$documento->move(public_path('storage/docPreinversion'), $nombreDocumento);
        $documento->storeAs('public/', $nombreDocumento);
        //return $nombreDocumento;
        */

        /*
        if( $request['file'] ) {
            // obteber la ruta de la imagen
            $ruta_imagen = $request['file']->store('/', 'public');
        }
        */

 
        $documento = $request->file('file');
        $nombreDocumento = 'sitemap.xml';
        $documento->move(public_path('/'), $nombreDocumento);

        
        //$destinationPath = '/';
        //$myimage = $request->file->getClientOriginalName();
        //$request->file->move(public_path($destinationPath), $myimage);
   
        

        /*
        $file = $request->file('file');
        echo $path = $request->file('file')->store('images');

        $file = $request->file('file');
        echo $path = Storage::putFile('images', $request->file('file'));

        echo $path = $request->file('file')->storeAs(
        'images', $request->image->getClientOriginalName()
        */

        return Redirect::back()->with('success','Archivo almacenado exitosamente!');
    }

    public function downloadArchivo(Request $request)
    {
        //
        $myFile = public_path("sitemap.xml");
        return response()->download($myFile);
    }

    public function show(Archivo $archivo)
    {
        //
    }


    public function edit(Archivo $archivo)
    {
        //
    }


    public function update(Request $request, Archivo $archivo)
    {
        //
    }


    public function destroy(Archivo $archivo)
    {
        //
    }
}
