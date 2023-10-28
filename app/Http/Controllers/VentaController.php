<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Comprobante;

class VentaController extends Controller
{
    public function saveVenta(Request $request)
    {
        $comprobante = [
            'personas_id' => $request->personas_id,
            'fecha' => date("Y-m-d H:i:s"),
            'serie' => $request->serie,
            'correlativo' => $this->getSerie($request->empresa_id, $request->serie),
            'monto' => $request->total,
            'gravado' => $request->gravado,
            'igv' => $request->igv,
            'invertido' => $request->invertido,
            'descuento' => 0.00,
            'nota' => $request->nota ?? null,
            'estado' => 1,
            'tipoDocumento_id' => $request->comprobante ?? null,
            'send' => 0,
            'empresa_id' => $request->empresa_id,
            'users_id' => $request->user_id,
            'tipoPago_id' => $request->tipoPago_id
        ];
        try {
            DB::beginTransaction();

            if ($request->personas_id == 1) {
                $persona = [
                    'documento' => $request->numero,
                    'nombres' => $request->cliente,
                    'telefono' => $request->telefono,
                ];
                $getPersona = DB::table('personas')->insertGetId($persona);
                $comprobante['personas_id'] = $getPersona;
            } else {
                $persona = [
                    'telefono' => $request->telefono ?? null,
                    'direccion' => $request->direccion ?? null,
                ];
                $getPersona = DB::table('personas')->where('id', $request->personas_id)->update($persona);
                $getPersona = $request->personas_id;
            }
            $comprobante = DB::table('comprobantes')->insertGetId($comprobante);
            foreach ($request->detalles as $key => $value) {
                $detalles = DB::table('detalles')->insertGetId([
                    'productos_id' => $value['id'],
                    'cantidad' =>  $value['cantidad'],
                    'precio' => $value['unitario'],
                    'subtotal' => $value['subtotal'],
                    'series' => json_encode($value['series']),
                    'comprobantes_id' => $comprobante
                ]);
            }
            DB::commit();
            $response = ['status' => true, 'data' => $comprobante];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function getCart($code)
    {
        try {
            $product = [];
            $product = DB::table('productos as p')
                ->join('lotes as l', 'l.productos_id', '=', 'p.id')
                ->select('p.*', 'l.precioVenta', 'l.precioCompra as invertido')
                ->where('p.codigo', '=', $code)
                ->get();
            if (count($product) == 0) {
                $product = DB::table('series as s')
                    ->join('lotes as l', 'l.id', '=', 's.lote_id')
                    ->join('productos as p', 'l.productos_id', '=', 'p.id')
                    ->select('p.*', 'l.precioVenta', 'l.precioCompra as invertido', DB::raw('1 as serie'))
                    ->where('s.codigo', '=', $code)
                    ->get();
            }
            $response = ['status' => true, 'data' => $product];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => false, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function getSerie($idEmpresa, $serie)
    {
        $serie = DB::select("select (correlativo + 1) as correlativo from comprobantes   where empresa_id = " . $idEmpresa . " and  serie = '" . $serie . "' and estado = 1 order by correlativo desc  limit 1");
        if (count($serie) > 0) {
            return $serie[0]->correlativo;
        } else {
            return 1;
        }
    }
    public function getComprobante($id)
    {
        try {

            $comprobante = DB::table('comprobantes')
                ->where('comprobantes.id', $id)
                ->join('personas as pe', 'pe.id', '=', 'comprobantes.personas_id')
                ->join('users as u', 'u.id', '=', 'comprobantes.users_id')
                ->join('empresas as em', 'em.id', '=', 'comprobantes.empresa_id')
                ->select('comprobantes.*', 'pe.*', 'em.*', 'pe.direccion as dir', 'u.username as user')
                ->get();

            $comprobante[0]->detalles = DB::table('detalles as d')->where('comprobantes_id', $id)->join('productos as s', 's.id', '=', 'd.productos_id')->get();
            $response = ['status' => true, 'data' => $comprobante[0]];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function Anular($idComprobante)
    {
        try {
            $comprobante = DB::table('comprobantes')->where('id', $idComprobante)->first();
            if ($comprobante->send == 1) {
                $detalles = DB::table('detalles')->where('comprobantes_id', $idComprobante)->get();
                $comprobante->fecha = date("Y-m-d H:i:s");
                $comprobante->id = NULL;
                $lastComprobante = $comprobante->tipoDocumento_id;
                $comprobante->numDocfectado = $comprobante->serie . '-' . $comprobante->correlativo;
                if ($comprobante->tipoDocumento_id == '01') {
                    $comprobante->serie = 'FNC1';
                    $comprobante->tipoDocumento_id = '07';
                    $comprobante->correlativo = $this->getSerie($comprobante->empresa_id, 'FNC1');
                }
                if ($comprobante->tipoDocumento_id == '03') {
                    $comprobante->serie = 'BNC1';
                    $comprobante->tipoDocumento_id = '07';
                    $comprobante->correlativo = $this->getSerie($comprobante->empresa_id, 'BNC1');
                }
                $comprobante = (array) $comprobante;
                DB::beginTransaction();
                $edit = DB::table('comprobante')->where('id', $idComprobante)->update(['estado' => 0]);
                $rest = DB::table('comprobante')->insertGetId($comprobante);
                foreach ($detalles->toArray() as  $value) {
                    $value->id = NULL;
                    $value->comprobantes_id = $rest;
                    $detalles = DB::table('detalles')->insertGetId((array) $value);
                }
                DB::commit();
                $nota = Comprobante::with(['empresa' => function ($query) {
                    $query->where('estado', 1);
                }, 'detalles.producto', 'persona'])
                    ->where('id', $rest)->first();
                $nota->tipDocAfectado = $lastComprobante;
                if ($nota->empresa) {
                    $comprobanteController = new ComprobanteController();
                    $comprobanteController->enviarComprobanteAPI($nota);
                }
            } else {
                $rest = DB::table('comprobantes')->where('id', $idComprobante)->update(['estado' => 0]);
            }
            $response = ['status' => true, 'data' => $rest];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}
