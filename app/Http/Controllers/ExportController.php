<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Alice\ApiManagerCurl;

class ExportController extends Controller
{
    public function getComprobante($id)
    {
        try {
            // Obtén el comprobante y sus detalles
            $comprobante = DB::table('comprobantes')
                ->where('comprobantes.id', $id)
                ->join('personas as pe', 'pe.id', '=', 'comprobantes.personas_id')
                ->join('users as u', 'u.id', '=', 'comprobantes.users_id')
                ->join('empresas as em', 'em.id', '=', 'comprobantes.empresa_id')
                ->select('comprobantes.*', 'pe.*', 'em.*', 'pe.direccion as dir', 'u.username as user')
                ->get();
    
            $comprobante[0]->detalles = DB::table('detalles as d')
                ->where('comprobantes_id', $id)
                ->join('productos as s', 's.id', '=', 'd.productos_id')
                ->get();
    
            // Genera el código QR
            $qrcode = base64_encode(QrCode::format('svg')->size(120)->errorCorrection('H')->generate('string'));
    
            // Personaliza el tamaño del papel
            $customPaper = array(0, 0, 170.07, 600);
    
            // Genera el PDF usando la vista 'documents.comprobante'
            $pdf = PDF::loadView('documents.comprobante', ['data' => $comprobante[0], 'qrcode' => $qrcode])->setPaper($customPaper);

            // Devuelve el PDF como una respuesta en streaming
            return $pdf->stream();
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
    }
    
    public function getComprobanteA4($id)
    {
        try {
            $comprobante = DB::table('comprobantes')
                ->where('comprobantes.id', $id)
                ->join('personas as pe', 'pe.id', '=', 'comprobantes.personas_id')
                ->join('users as u', 'u.id', '=', 'comprobantes.users_id')
                ->join('empresas as em', 'em.id', '=', 'comprobantes.empresa_id')
                ->select('comprobantes.*', 'pe.*', 'em.*', 'pe.direccion as dir', 'u.username as user')
                ->first();
            $pago = explode(".", $comprobante->monto);
            $formatter = new NumeroALetras();
            $comprobante->montoLetras = $formatter->toInvoice($pago[0], $pago[1], 'soles');
            $comprobante->detalles = DB::table('detalles as d')->where('comprobantes_id', $id)->join('productos as s', 's.id', '=', 'd.productos_id')->get();
            $qrcode = base64_encode(QrCode::format('svg')->size(120)->errorCorrection('H')->generate('string'));
            $customPaper = array(0, 0, 170.07, 600);
            $pdf = PDF::loadView('documents.a4', ['data' => $comprobante, 'qrcode' => $qrcode])->setPaper('A4');
            return $pdf->stream();
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
    }
    public function getCartaGarantia($id)
    {
        try {

            $comprobante = DB::table('comprobantes')
                ->where('comprobantes.id', $id)
                ->join('personas as pe', 'pe.id', '=', 'comprobantes.personas_id')
                ->join('users as u', 'u.id', '=', 'comprobantes.users_id')
                ->join('empresas as em', 'em.id', '=', 'comprobantes.empresa_id')
                ->select('comprobantes.*', 'pe.*', 'em.*', 'pe.direccion as dir', 'u.username as user')
                ->get();

            $comprobante[0]->detalles = DB::table('detalles as d')->where('comprobantes_id', $id)
                ->join('productos as s', 's.id', '=', 'd.productos_id')
                ->get();
            //dd(Carbon::parse('2019-07-23 14:51')->locale('es_PE')->isoFormat('LLLL'));
            $pdf = PDF::loadView('documents.carta', ['data' => $comprobante[0]])->setPaper('A4');
            return $pdf->stream();
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
    }
    public function voucherPago($id, $idPago)
    {
        try {
            $totalDeuda = 0;
            $todalPagos = 0;
            $dataDeudas = DB::table('comprobantes')->where('personas_id', $id)->where('tipoPago_id', 2)->get();
            $dataPagos = DB::table('pagos')->where('personas_id', $id)->get();
            $pago = DB::table('pagos')->where('id', $idPago)->first();
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
                'empresas' => DB::table('empresas')->first(),
                'persona' => DB::table('personas')->where('id', $id)->first(),
                'totalDeuda' =>  $totalDeuda,
                'totalPagos' =>  $todalPagos,
                'resta' =>  $totalDeuda - $todalPagos,
                'pago' =>  $pago,
            ];
            $response = ['status' => true, 'data' => $qr];
            $codeResponse = 200;
            $customPaper = array(0, 0, 170.07, 600);
            $pdf = PDF::loadView('documents.pago', $qr)->setPaper($customPaper);
            return $pdf->stream();
        } catch (\Exception $e) {
            $response = ['status' => true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
    public function getFile($numeracion, $tipoDoc, $empresa_id)
    {

        $empresa  = DB::table('empresas')->where('id', $empresa_id)->select('RUC as emp_ruc', 'token')->first();
        //dd($empresa);
        $curl = new ApiManagerCurl();
        $filename = $curl->downloadXML($numeracion, $tipoDoc, $empresa);

        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            unlink($filename);
            exit;
        }
    }
}
