<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class ReportsController extends Controller
{
    public function getReportInventory()
    {
        try {
            $data = DB::table('Materiales')
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
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function getReportInventoryPdf()
    {

        $data = DB::table('Materiales')
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
        foreach ($data as $key => $value) {
            $value->id = 'MAT-' . str_pad($value->id, 6, '0', STR_PAD_LEFT);
        };
        $pdf = PDF::loadView('reports.inventory', ['data' => $data]);
        return $pdf->stream();
    }
    public function getReportKardex()
    {
        try {
            $data = DB::table(DB::raw('(SELECT e.fecha, em.id AS idg, "P/I Compra" AS tipoDocumento, CONCAT("EM-", LPAD(e.id, 6, "0")) as nDocumento, m.material,
            (SELECT numDocIdentificacion FROM Empresas p WHERE p.id = e.id LIMIT 1) AS doc,
            em.cantidad AS entrada, NULL AS salida, m.stock AS saldo
            FROM Materiales AS m
            INNER JOIN EntradasMateriales AS em ON em.materiales_id = m.id
            INNER JOIN Entradas AS e ON e.id = em.Entradas_id
            UNION
            SELECT s.fecha, sm.id AS idg, "Parte de Salida" AS tipoDocumento, CONCAT("EM-", LPAD(s.id, 6, "0")) as nDocumento, m.material, NULL AS doc, NULL AS entrada, sm.cantidad AS salida, m.stock AS saldo
            FROM Materiales AS m
            INNER JOIN SalidasMateriales AS sm ON sm.materiales_id = m.id
            INNER JOIN Salidas as s ON s.id = sm.Salidas_id) AS combined'))
                ->orderBy('combined.material', 'asc')
                ->get();
            $id = 0;
            foreach ($data as $key => $row) {
                $row->id = $key + 1;
            }
            $response = ['status' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function getReportKardexPdf()
    {

        $data = DB::table(DB::raw('(SELECT e.fecha, em.id AS idg, "P/I Compra" AS tipoDocumento, CONCAT("EM-", LPAD(e.id, 6, "0")) as nDocumento, m.material,
            (SELECT numDocIdentificacion FROM Empresas p WHERE p.id = e.id LIMIT 1) AS doc,
            em.cantidad AS entrada, NULL AS salida, m.stock AS saldo
            FROM Materiales AS m
            INNER JOIN EntradasMateriales AS em ON em.materiales_id = m.id
            INNER JOIN Entradas AS e ON e.id = em.Entradas_id
            UNION
            SELECT s.fecha, sm.id AS idg, "Parte de Salida" AS tipoDocumento, CONCAT("EM-", LPAD(s.id, 6, "0")) as nDocumento, m.material, NULL AS doc, NULL AS entrada, sm.cantidad AS salida, m.stock AS saldo
            FROM Materiales AS m
            INNER JOIN SalidasMateriales AS sm ON sm.materiales_id = m.id
            INNER JOIN Salidas as s ON s.id = sm.Salidas_id) AS combined'))
            ->orderBy('combined.material', 'asc')
            ->get();

        $pdf = PDF::loadView('reports.kardex', ['data' => $data])->setPaper('letter', 'landscape');
        return $pdf->stream();
    }
    public function getReportOrdenesPDF($id)
    {
        $empresa = DB::table('Empresas')->where('id', $id)->first();
        $data = DB::table('OrdenesCompra as oc')
            ->select(
                DB::raw('"O/C Bienes" as tipoDoc'),
                DB::raw('CONCAT("OC-", LPAD(oc.id, 6, "0")) as numDoc'),
                'oc.fecha',
                DB::raw('(Select CONCAT("MAT-", LPAD(mat.id, 6, "0")) from Materiales as mat where mat.id = ocm.Materiales_id limit 1) as codigo'),
                DB::raw('(Select ma.material from Materiales as ma where ma.id = ocm.Materiales_id limit 1) as material'),
                'ocm.cantidad as cantidadPe',
                DB::raw('0 as cantidadAt'),
                'ocm.cantidad as cantidadTo',
                'oc.total'
            )
            ->join('OrdenesCompraMateriales as ocm', 'ocm.OrdenesCompra_id', '=', 'oc.id')
            ->where('oc.Empresas_id', $id)
            ->orderBy('oc.id', 'desc')
            ->get();
        if(count($data) > 0){
            $pdf = PDF::loadView('reports.orders', ['data' => $data, 'empresa' => $empresa])->setPaper('letter', 'landscape');
            return $pdf->stream();
        }else{
            return "sin datos";
        }
       
    }
}
