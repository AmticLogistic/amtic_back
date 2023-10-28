<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function reporteDocumentos(Request $request)
    {
        try {
            $registros = DB::table('comprobantes as c')
                ->join('personas as p', 'p.id', '=', 'c.personas_id')
                ->select('c.*', 'p.nombres', 'p.paterno', 'p.materno')
                ->get();
            //$registros = DB::select("CALL reporteComprobante('$request->init 00:00:00','$request->end 23:59:59',$request->empresa,'$request->comprobante','$request->cliente')");
            $response = ['status' => true, 'data' => $registros];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function reporte1(Request $request)
    {
        try {
            $registros = DB::select("CALL reporte1('$request->init','$request->end')");
            $balance = DB::select("CALL reporte2('$request->init','$request->end')");
            $reporte = DB::select("CALL reporte3('$request->init','$request->end')");
            $renta = DB::select("CALL reporte4('$request->init','$request->end')");

            $response = ['status' => true, 'data' => $registros, 'balance' => $balance, 'reporte' => $reporte, 'renta' => $renta];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}
