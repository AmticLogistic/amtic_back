<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\PDF;

class SoporteController extends Controller
{
    public function listSoporte(){
        try{
            $data = DB::table('soporte')->join('personas as p','p.id','soporte.persona_id')->select('soporte.*','p.documento as dni','p.nombres','p.paterno','p.materno','p.telefono','p.direccion')->orderBy('id','desc')->get();
            $response = [ 'status'=> true, 'data' => $data];
            $codeResponse = 200;
        }catch(\Exception $e){
            $response = [ 'status'=> true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    public function saveData(Request $request){
        $comprobante = [
            'fechaIngreso' => $request->fechaIngreso,
            'persona_id' => $request->persona_id,
            'numero' => $request->numero,
            'monto' => $request->monto,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'serie' => $request->serie,
            'descripcion' => $request->descripcion,
            'caracteristicas' => $request->caracteristicas,
            'box' => $request->box,
            'comentarios' => $request->comentarios,
            'desperfecto' => $request->desperfecto,
            'observacion' => $request->observacion,
            'soporte' => $request->soporte
        ];
        try{
            DB::beginTransaction(); 

            if( $request->persona_id == 1 ){
                $persona = [
                    'documento'=>$request->numero,
                    'nombres'=>$request->cliente,
                    'telefono'=>$request->telefono,
                    'direccion'=>$request->direccion,
                ];
                $getPersona = DB::table('personas')->insertGetId($persona);
                $comprobante['persona_id'] = $getPersona;
            }else{
                $persona = [
                    'telefono'=>$request->telefono ?? null,
                    'direccion' => $request->direccion ?? null,
                ];
                $getPersona = DB::table('personas')->where('id', $request->persona_id)->update($persona);
                $getPersona = $request->persona_id;
            }
            if($request->id){
                $comprobante = DB::table('soporte')->where('id',$request->id)->update($comprobante);
            }else{
                $comprobante = DB::table('soporte')->insertGetId($comprobante);
            }
            DB::commit();
            $response = [ 'status'=> true, 'data' => $comprobante];
            $codeResponse = 200;

        } catch(\Exception $e){
            DB::rollBack();
            $response = [ 'status'=> true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    public function getExportDoc($id){
        try{
           
            $data = DB::table('soporte')->where('soporte.id',$id)->join('personas as p','p.id','=','soporte.persona_id')->select('soporte.*','p.documento','p.nombres','p.paterno','p.telefono','p.direccion')->first();
            $data->box = json_decode($data->box);
            $pdf = PDF::loadView('documents.suport', ['data'=> $data ] )->setPaper('A4');
            return $pdf->stream();

        }catch(\Exception $e){
            $response = [ 'status'=> true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
    }
}
