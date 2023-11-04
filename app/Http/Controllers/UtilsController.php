<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtilsController extends Controller
{
    public function listCategorias()
    {
        try {
            $data = DB::table('Categorias')->where('estado', 1)->orderBy('categoria', 'asc')->get();
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
            $qr = DB::table('Categorias')->insertGetId(['categoria' => $request->nombre, 'estado' => 1]);
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
            $data = ['categoria' => $request->nombre, 'estado' => $request->estado];
            $qr = DB::table('Categorias')->where('id', $request->id)->update($data);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function listMarcas()
    {
        try {
            $data = DB::table('Marcas')->where('estado', 1)->orderBy('marca', 'asc')->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function saveMarcas(Request $request)
    {
        try {
            $qr = DB::table('Marcas')->insertGetId(['marca' => $request->nombre, 'estado' => 1]);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function editMarcas(Request $request)
    {
        try {
            $data = ['marca' => $request->nombre, 'estado' => $request->estado];
            $qr = DB::table('Marcas')->where('id', $request->id)->update($data);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function listMateriales()
    {
        try {
            $materiales = DB::table('Materiales')
                ->join('Marcas', 'Materiales.Marcas_id', '=', 'Marcas.id')
                ->join('Unidades', 'Materiales.Unidades_id', '=', 'Unidades.id')
                ->join('Categorias', 'Materiales.Categorias_id', '=', 'Categorias.id')
                ->select(
                    'Materiales.*',
                    'Marcas.marca as marca_nombre',
                    'Unidades.unidad as unidad_nombre',
                    'Categorias.categoria as categoria_nombre'
                )
                ->where('Unidades.unidad', '<>', 'SERV')
                ->get();
            $response = ['status' => true, 'data' => $materiales];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function listServicios()
    {
        try {
            $materiales = DB::table('Materiales')
                ->join('Unidades', 'Materiales.Unidades_id', '=', 'Unidades.id')
                ->join('Categorias', 'Materiales.Categorias_id', '=', 'Categorias.id')
                ->select(
                    'Materiales.*',
                    'Unidades.unidad as unidad_nombre',
                    'Categorias.categoria as categoria_nombre'
                )
                ->where('Materiales.Unidades_id', '16')
                ->get();
            $response = ['status' => true, 'data' => $materiales];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function utils()
    {
        try {
            $data = [
                'marcas' => DB::table('Marcas')->where('estado', 1)->orderBy('marca', 'asc')->get(),
                'categorias' => DB::table('Categorias')->where('estado', 1)->orderBy('categoria', 'asc')->get(),
                'unidades' => DB::table('Unidades')->where('estado', 1)->orderBy('unidad', 'asc')->get(),
            ];

            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function saveMaterial(Request $request)
    {
        try {
            $qr = DB::table('Materiales')->insertGetId([
                'material' => $request->nombre,
                'stock' => 0,
                'stockMinimo' => 0,
                'codBarras' => $request->codigo ?? null,
                'urlFichaTecnica' => $request->ficha ?? null,
                'urlImagen' => $request->imagen ?? null,
                'Marcas_id' => $request->marca,
                'Unidades_id' => $request->unidad,
                'Categorias_id' => $request->categoria,
            ]);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function getPersons()
    {
        try {
            $data = DB::table('Personas')->orderBy('apePaterno','asc')->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function getProviders()
    {
        try {
            $data = DB::table('Empresas')->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function getCCostos()
    {
        try {
            $data = DB::table('CCostosPrimarios')->orderBy('centroCosto', 'asc')->get();
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function getTransport()
    {
        try {
            $result = [
                "transport" => [],
                "business" => []  // Cambiado "bussiness" a "business" para corregir la falta de ortografía
            ];

            $data = DB::table('Empresas')->get();

            foreach ($data as $value) {
                if ($value->esTransportista == 1) {
                    array_push($result["transport"], $value);
                } else {
                    array_push($result["business"], $value);  // Cambiado "transport" a "business" para separar transportistas de negocios
                }
            }

            $response = ['status' => true, 'data' => $result];  // Devuelve el arreglo $result en lugar de $data
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => false, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];  // Cambiado 'true' a 'false' para reflejar el estado de error
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function getOrderOne($id)
    {
        try {
            $data = DB::table('OrdenesCompraMateriales')
                ->join('Materiales', 'OrdenesCompraMateriales.Materiales_id', '=', 'Materiales.id')
                ->join('Unidades', 'Materiales.Unidades_id', '=', 'Unidades.id')
                ->select('OrdenesCompraMateriales.*', 'Materiales.material as material', 'Unidades.unidad')
                ->where('OrdenesCompraMateriales.OrdenesCompra_id', $id)
                ->get();

            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function listInput(Request $request)
    {
        try {
            $qr = DB::table('Materiales')->insertGetId([
                'material' => $request->nombre,
                'stock' => 0,
                'stockMinimo' => 0,
                'codBarras' => $request->codigo ?? null,
                'urlFichaTecnica' => $request->ficha ?? null,
                'urlImagen' => $request->imagen ?? null,
                'Marcas_id' => $request->marca,
                'Unidades_id' => $request->unidad,
                'Categorias_id' => $request->categoria,
            ]);
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}
