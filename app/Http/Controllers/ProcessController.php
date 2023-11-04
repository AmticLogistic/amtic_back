<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessController extends Controller
{
    public function listRequerimientos()
    {
        try {
            $data = DB::table('Requerimientos')
                ->join('Personas', 'Requerimientos.persona_id', '=', 'Personas.id')
                ->select(
                    'Requerimientos.*',
                    'Personas.nombres as nombre',
                    'Personas.apePaterno as paterno',
                    'Personas.apeMaterno as materno',
                )
                ->get();
            if (count($data) > 0) {
                foreach ($data as $key => $row) {
                    $row->list = DB::table('RequerimientosMateriales')
                        ->where('Requerimientos_id', $row->id)
                        ->join('Materiales', 'Materiales.id', '=', 'RequerimientosMateriales.Materiales_id')
                        ->select('Materiales.material', 'RequerimientosMateriales.cantidad')
                        ->get();
                }
            }
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function saveRequerimiento(Request $request)
    {
        $requerimientos = [
            'fecha' => $request->fecha ?? date("Y-m-d"),
            'areaSolicita' => $request->areaSolicita ?? null,
            'persona_id' => $request->persona_id,
            'observacion' => $request->observacion ?? null,
        ];
        try {
            DB::beginTransaction();
            $requerimiento = DB::table('Requerimientos')->insertGetId($requerimientos);
            foreach ($request->detalles as $key => $value) {
                $detalles = DB::table('RequerimientosMateriales')->insertGetId([
                    'cantidad' => $value['cantidad'],
                    'observacion' =>  null,
                    'Materiales_id' => $value['id'],
                    'Requerimientos_id' => $requerimiento
                ]);
            }
            DB::commit();
            $response = ['status' => true, 'data' => $requerimiento];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function saveOrden(Request $request)
    {
        $requerimientos = [
            'fecha' => $request->fecha ?? date("Y-m-d"),
            'total' => $request->total,
            'sitioEntrega' => $request->sitioEntrega,
            'revisadoPor' => $request->revisadoPor,
            'aprobadoPor' => $request->aprobadoPor,
            'observacion' => $request->observacion,
            'Monedas_id' => $request->Monedas_id,
            'Empresas_id' => $request->Empresas_id,
            'MetodosPago_id' => $request->MetodosPago_id
        ];
        try {
            DB::beginTransaction();
            $orden = DB::table('OrdenesCompra')->insertGetId($requerimientos);
            foreach ($request->detalles as $key => $value) {
                $detalles = DB::table('OrdenesCompraMateriales')->insertGetId([
                    'cantidad' => $value['cantidad'],
                    'precioUnitario' => $value['pu'],
                    'subtotal' => $value['subtotal'],
                    'observacion' =>  null,
                    'OrdenesCompra_id' => $orden,
                    'Materiales_id' => $value['id']
                ]);
            }
            DB::commit();
            $response = ['status' => true, 'data' => $orden];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function saveInput(Request $request)
    {
        $entrada = [
            'fecha' => $request->fecha ?? date("Y-m-d"),
            'total' => $request->total,
            'puestoEn' => $request->puestoEn,
            'guiaRemision' => $request->guiaRemision,
            'serieComprobante' => $request->serieComprobante,
            'correlativoComprobante' => $request->correlativoComprobante,
            'observacion' => $request->observacion,
            'Proveedores_id' => $request->Proveedores_id,
            'Transportistas_id' => $request->Transportistas_id,
            'Monedas_id' => $request->Monedas_id,
        ];
        try {
            DB::beginTransaction();
            $register = DB::table('Entradas')->insertGetId($entrada);
            foreach ($request->detalles as $key => $value) {
                $detalles = DB::table('EntradasMateriales')->insertGetId([
                    'cantidad' => $value['cantidad'],
                    'precioUnitario' => $value['precioUnitario'],
                    'observacion' =>  null,
                    'Materiales_id' => $value['Materiales_id'],
                    'Entradas_id' => $register,

                ]);
            }
            DB::commit();
            $response = ['status' => true, 'data' => $register];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function saveOutput(Request $request)
    {
        $salida = [
            'fecha' => $request->fecha ?? date("Y-m-d"),
            'observacion' => $request->observacion,
            'Personas_id' => $request->Personas_id,
            'TiposMovimientos_id' => 2,
            'CCostosPrimarios_id' => $request->CCostosPrimarios_id,
        ];
        try {
            DB::beginTransaction();
            $register = DB::table('Salidas')->insertGetId($salida);
            foreach ($request->detalles as $key => $value) {
                $detalles = DB::table('SalidasMateriales')->insertGetId([
                    'cantidad' => $value['cantidad'],
                    'observacion' =>  null,
                    'Materiales_id' => $value['Material_id'],
                    'Salidas_id' => $register,
                ]);
            }
            DB::commit();
            $response = ['status' => true, 'data' => $register];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function listOutput()
    {
        try {
            $data = DB::table('Salidas')
                ->join('Personas', 'Salidas.personas_id', '=', 'Personas.id')
                ->join('CCostosPrimarios', 'Salidas.CCostosPrimarios_id', '=', 'CCostosPrimarios.id')
                ->select(
                    'Salidas.*',
                    'CCostosPrimarios.centroCosto as cc',
                    'Personas.nombres as nombre',
                    'Personas.apePaterno as paterno',
                    'Personas.apeMaterno as materno',
                )
                ->get();
            if (count($data) > 0) {
                foreach ($data as $row) {
                    $row->list = DB::table('SalidasMateriales')
                        ->where('Salidas_id', $row->id)
                        ->join('Materiales', 'Materiales.id', '=', 'SalidasMateriales.Materiales_id')
                        ->select('Materiales.material', 'SalidasMateriales.cantidad')
                        ->get();
                }
            }
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

    public function listOrden()
    {
        try {
            $data = DB::table('OrdenesCompra')
                ->join('Empresas', 'OrdenesCompra.Empresas_id', '=', 'Empresas.id')
                ->join('Monedas', 'OrdenesCompra.Monedas_id', '=', 'Monedas.id')
                ->select(
                    'OrdenesCompra.*',
                    'Empresas.razonSocial as empresa',
                    'Monedas.moneda as moneda',
                )
                ->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function listInputs()
    {
        try {
            $data = DB::table('Requerimientos')
                ->join('Personas', 'Requerimientos.persona_id', '=', 'Personas.id')
                ->select(
                    'Requerimientos.*',
                    'Personas.nombres as nombre',
                    'Personas.apePaterno as paterno',
                    'Personas.apeMaterno as materno',
                )
                ->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function obtenerEntradas()
    {
        $entradas = DB::table('Entradas as e')
            ->select('e.*')
            ->selectRaw('(SELECT em.razonSocial FROM Empresas AS em WHERE em.id = e.Proveedores_id LIMIT 1) AS proveedor')
            ->selectRaw('(SELECT em.razonSocial FROM Empresas AS em WHERE em.id = e.Transportistas_id LIMIT 1) AS transportista')
            ->selectRaw('CASE
            WHEN e.Monedas_id = 1 THEN "SOLES"
            WHEN e.Monedas_id = 2 THEN "DOLARES"
            ELSE NULL
        END AS moneda')
            ->get();
        return response()->json($entradas);
    }
    
}
