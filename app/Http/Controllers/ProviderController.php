<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
    public function listProvider()
    {
        try {
            $data = DB::table('proveedores')->where('estado', 1)->orderBy('nombre', 'asc')->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function saveProvider(Request $request)
    {
        try {
            $qr = DB::table('proveedores')->insertGetId([
                'nombre' => $request->nombre, 
                'direccion' => $request->direccion, 
                'telefono' => $request->telefono,
                'estado' => 1
            ]);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function editProvider(Request $request)
    {
        try {
            $data = [
                'nombre' => $request->nombre, 
                'direccion' => $request->direccion, 
                'telefono' => $request->telefono,
                'estado' => 1
            ];
            $qr = DB::table('proveedores')->where('id', $request->id)->update($data);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}
