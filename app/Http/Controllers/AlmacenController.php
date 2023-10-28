<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;

class AlmacenController extends Controller
{
    public function listProducto()
    {

        $hash = new Hashids("test", 6, "ABCDEFGH123456789");
        try {
            $data = DB::select('CALL allProductos()');
            foreach ($data as $key => $value) {
                $value->code = $hash->encode($value->id);
            }
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function oneProducto($id)
    {
        try {
            $data = [
                'producto' =>  DB::table('productos')->where('id', $id)->first(),
                'lotes' => DB::table('lotes')->where('productos_id', '=', $id)->where('estado', '!=', 2)->orderBy('id', 'desc')->get(),
                'caracteristicas' => DB::table('caracteristicas')->where('productos_id', $id)->get(),
            ];
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function saveProducto(Request $request)
    {
        try {
            $producto = [
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'zone' => $request->zone,
                'descripcion' => $request->descripcion,
                'marcas_id' => $request->marca_id,
                'line_id' => $request->line,
            ];
            $lote = [
                'fechaIngreso' => date("Y-m-d H:i:s"),
                'stockIngreso' => $request->stock,
                'provider_id' => $request->provider_id,
                'stockActual' => $request->stock,
                'nota' => '-',
                'precioMayor' => $request->precio_mayor,
                'precioVenta' => $request->precio_venta,
                'precioCompra' => $request->precio_compra,
                'productos_id' => $request->productos_id,
                'estado' => 1
            ];
            DB::beginTransaction();

            $addProducto = DB::table('productos')->insertGetId($producto);
            $lote['productos_id'] = $addProducto;
            $addLote = DB::table('lotes')->insertGetId($lote);

            DB::commit();

            $response = ['status' => true, 'data' => $addProducto];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function updateProducto(Request $request)
    {
        try {
            $producto = [
                'codigo' => $request->codigo,
                'zone' => $request->zone,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'marcas_id' => $request->marcas_id,
                'estado' => 1
            ];
            DB::beginTransaction();
            $addProducto = DB::table('productos')->where('id', $request->id)->update($producto);
            DB::commit();

            $response = ['status' => true, 'data' => $addProducto];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function addLote(Request $request)
    {
        try {
            $registro = [
                'provider_id' => $request->provider_id,
                'fechaIngreso' => date("Y-m-d H:i:s"),
                'stockIngreso' => $request->stockIngreso,
                'precioMayor' => $request->precioMayor,
                'precioVenta' => $request->precioVenta,
                'precioCompra' => $request->precioCompra,
                'productos_id' => $request->productos_id,
                'estado' => 1,
            ];
            if ($request->id || $request->id != 0) {
                $registro['stockActual'] = $request->stockActual;
                $data = DB::table('lotes')->where('id', $request->id)->update($registro);
            } else {
                $registro['stockActual'] = $request->stockIngreso;
                $data = DB::table('lotes')->where('productos_id', $request->productos_id)->update(['estado' => 0]);
                $data = DB::table('lotes')->insertGetId($registro);
            }
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function addSerie(Request $request)
    {
        try {
            $data = DB::table('series')->insertGetId(['codigo' => $request->codigo, 'estado' => 1, 'lote_id' => $request->lote_id]);
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

    public function AllSerie($idLote)
    {
        try {
            $data = DB::table('series')->where('lote_id', $idLote)->where('estado', 1)->orderBy('id', 'DESC')->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

    public function DeleteSerie($idLote)
    {
        try {
            $data = DB::table('series')->where('id', $idLote)->update(["estado" => 0]);
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

    public function addCaracteristica(Request $request)
    {
        try {
            $data = DB::table('caracteristicas')->insertGetId(['nombre' => $request->nombre, 'descripcion' => $request->descripcion, 'estado' => 1, 'productos_id' => $request->productos_id]);
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function selectLote(Request $request)
    {
        try {
            $reset = DB::table('lotes')->where('productos_id', $request->productos_id)->where('estado', '!=', 2)->update(['estado' => 0]);
            $asigne = DB::table('lotes')->where('id', $request->id)->update(['estado' => 1]);
            $response = ['status' => true, 'data' => $asigne];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}
