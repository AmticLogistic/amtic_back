<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    public function listComprobantes()
    {
        try {
            $data = DB::table('tipodocumento')->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function listEmpresas()
    {
        try {
            $data = DB::table('empresas')->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function listCategorias()
    {
        try {
            $data = DB::table('marcas')->where('estado', 1)->orderBy('codigo', 'asc')->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function listLines()
    {
        try {
            $data = DB::table('line')->orderBy('codigo', 'asc')->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function saveCategorias(Request $request)
    {
        try {
            $qr = DB::table('marcas')->insertGetId(['codigo' => $request->codigo, 'nombre' => $request->nombre, 'estado' => 1]);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function editCategorias(Request $request)
    {
        try {
            $data = ['codigo' => $request->codigo, 'nombre' => $request->nombre, 'estado' => $request->estado];
            $qr = DB::table('marcas')->where('id', $request->id)->update($data);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function deleteGeneral(Request $request)
    {
        try {
            $qr = DB::table($request->tabla)->where($request->campo, $request->id)->delete();
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function buscarCliente($tipo, $documento, $licencia = null)
    {
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im9uZWxpbmUuZnJlZWxhbmNlckBnbWFpbC5jb20ifQ.SZjMhu8PV9BNBtbhFFa2VRtZ_UJwB9Z07ZB85WWYRcE";
        try {
            $getCliente = DB::table('personas')->where('documento', $documento)->get();
            if (count($getCliente) > 0) {
                $dataCliente = $getCliente[0];
                $dataCliente->nombresCompletos = $dataCliente->nombres . ' ' . $dataCliente->paterno . ' ' . $dataCliente->materno;
            } else {
                if ($tipo == '01') {
                    $jsonString = file_get_contents("https://dniruc.apisperu.com/api/v1/ruc/" . $documento . "?token=$token");
                    $setCliente = (array) json_decode($jsonString, true);
                    $dataCliente = $this->savePerson($tipo, $documento, $setCliente, 1);
                } else {
                    $jsonString = file_get_contents("https://api.apis.net.pe/v1/dni?numero=" . $documento);
                    $setCliente = (array) json_decode($jsonString, true);
                    $dataCliente = $this->savePerson($tipo, $documento, $setCliente, 1);
                }
                $dataCliente['nombresCompletos'] = addslashes($dataCliente['nombres']);
            }
            $response = ['status' => true, 'data' => $dataCliente];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function buscarClienteLast($tipo, $documento)
    {
        try {
            $getCliente = DB::table('personas')->where('documento', $documento)->get();
            if (count($getCliente) > 0) {
                $dataCliente = $getCliente[0];
                $dataCliente->nombresCompletos = $dataCliente->nombres . ' ' . $dataCliente->paterno . ' ' . $dataCliente->materno;
            } else {
                $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.MTYzMA.Z91bggUHVslRNsIRNi38ATsWKVqst0ZLeHjbHc3bN_4';
                if ($tipo == '01') {
                    $jsonString = file_get_contents("https://dniruc.apisperu.com/api/v1/ruc/" . $documento . "?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im9uZWxpbmUuZnJlZWxhbmNlckBnbWFpbC5jb20ifQ.SZjMhu8PV9BNBtbhFFa2VRtZ_UJwB9Z07ZB85WWYRcE");
                    $setCliente = (array) json_decode($jsonString, true);
                } else {
                    $jsonString = file_get_contents("https://dniruc.apisperu.com/api/v1/dni/" . $documento . "?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im9uZWxpbmUuZnJlZWxhbmNlckBnbWFpbC5jb20ifQ.SZjMhu8PV9BNBtbhFFa2VRtZ_UJwB9Z07ZB85WWYRcE");
                    $setCliente = (array) json_decode($jsonString, true);
                }
                $dataCliente = $this->savePerson($tipo, $documento, $setCliente);
                $dataCliente['nombresCompletos'] = addslashes($dataCliente['nombres']);
            }
            $response = ['status' => true, 'data' => $dataCliente];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function savePerson($tipo, $documento, $persona)
    {
        if ($tipo == '01') {
            $setPersona = ['documento' => $documento, 'nombres' => addslashes($persona['razonSocial']), 'paterno' => '', 'materno' => '', 'direccion' => addslashes($persona['direccion'])];
        } else {
            $setPersona = ['documento' => $documento, 'paterno' => $persona['apellidoPaterno'], 'materno' => $persona['apellidoMaterno'], 'nombres' => $persona['nombres'], 'direccion' => ''];
        }
        $persona_id = DB::table('personas')->insertGetId($setPersona);
        $getCliente = ['id' => $persona_id, 'documento' => $documento, 'nombres' => $setPersona['nombres'] . ' ' . $setPersona['paterno'] . ' ' . $setPersona['materno'], 'direccion' => $setPersona['direccion'] ?? null];
        return $getCliente;
    }
    public function addBalance(Request $request)
    {
        try {
            $qr = DB::table('balance')->insert(['descripcion' => $request->descripcion, 'monto' => $request->monto, 'fecha' => $request->fecha, 'tipo' => $request->tipo]);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function deleteLote($id)
    {
        try {
            $qr = DB::table('lotes')->where('id', $id)->update(['estado' => 2]);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function editLote(Request $request)
    {
        try {
            $qr = DB::table('lotes')->where('id', $request->id)->update(['stockIngreso' => $request->stockIngreso, 'stockActual' => $request->stockActual, 'nota' => $request->nota, 'precioMayor' => $request->precioMayor, 'precioVenta' => $request->precioVenta, 'precioCompra' => $request->precioCompra]);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function getCreditos()
    {
        try {
            $qr = DB::select("CALL getCreditos()");
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function getCreditosPersona($id)
    {
        try {
            $totalDeuda = 0;
            $todalPagos = 0;
            $dataDeudas = DB::table('comprobantes')->where('personas_id', $id)->where('tipoPago_id', 2)->get();
            $dataPagos = DB::table('pagos')->where('personas_id', $id)->get();
            if (count($dataDeudas) > 0) {
                foreach ($dataDeudas as $row) {
                    $totalDeuda += floatval($row->monto);
                }
            }
            if (count($dataPagos) > 0) {
                foreach ($dataPagos as $row) {
                    $todalPagos += floatval($row->monto);
                }
            }
            $qr = [
                'persona' => DB::table('personas')->where('id', $id)->first(),
                'comprobantes' => $dataDeudas,
                'pagos' =>  $dataPagos,
                'totalDeuda' =>  $totalDeuda,
                'totalPagos' =>  $todalPagos,
                'resta' =>  $totalDeuda - $todalPagos,
            ];
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function savePago(Request $request)
    {
        try {
            $data = [
                'fecha' => $request->fecha,
                'monto' => $request->monto,
                'personas_id' => $request->personas_id,
            ];
            $qr = DB::table('pagos')->insert($data);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function cancelarPago($id)
    {
        try {
            $qr = DB::table('comprobantes')->where('id', $id)->update(['cancelado' => 1]);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}
