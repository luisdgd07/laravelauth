<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nota;
class NotaController extends Controller
{
    public function index(Request $request)
{   
    if($request->ajax()){
        return Nota::where('user_id', auth()->id())->get();
    }else{
        return view('home');
    }
}   
public function store(Request $request)
{
    $nota = new Nota();
    $nota->nombre = $request->nombre;
    $nota->descripcion = $request->descripcion;
    $nota->user_id = auth()->id();
    $nota->save();

    return $nota;
}  
public function update(Request $request, $id)
{
    $nota = Nota::find($id);
    $nota->nombre = $request->nombre;
    $nota->descripcion = $request->descripcion;
    $nota->save();
    return $nota;
}
public function destroy($id)
{
    $nota = Nota::find($id);
    $nota->delete();
}
}
