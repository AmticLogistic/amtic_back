<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use \Firebase\JWT\JWT;

class LoginController extends Controller
{
    public function signIn(Request $request){
        $data = DB::table('Usuarios')->where('usuario', $request->username)->get();
        if(count($data) > 0){
            $pass = hash('sha256', $request->password);
            if($data[0]->password == $pass && $data[0]->estado == 1){
                $persona = DB::table('Personas')->where('id',$data[0]->id)->first();
                $data[0]->person = $persona; 
                
                $data[0]->password = "******";
                $payload = [
                    'iss' => $request->username, // Emisor del token
                    'sub' => 'subjet', // Sujeto del token
                    'iat' => time(), // Tiempo de emisión del token
                    'exp' => time() + 60 * 60 * 12// Tiempo de expiración del token (1 hora)
                ];
                
                $secretKey = 'tu-clave-secreta';
                
                $token = JWT::encode($payload, $secretKey,'HS256');
                $response = [ 'status'=> true, 'user'=> $data[0], 'token' =>  $token, "expirationTime" => time() + 60 * 60 * 12 ];
                $code = 200;
            }else{
                $response = [ 'status'=> false, 'res'=>'La constraseña ingresada es incorrecta'];
                $code = 400;
            }
            
        }else{
            $response = [ 'status'=> false, 'res'=>'El usuario ingresado no existe' ];
            $code = 400;
        }
        return response()->json($response,$code);
    }
    public function listUsers(Request $request){
        return response()->json( $request);
    }
    public function generateUserConduc(Request $request){
        $pass = hash('sha256', $request->user);
        $user = [
            'nombre' => $request->nombre,
            'user' => $request->user,
            'rang' => $request->rang,
            'password' => $pass,
            'estado' => 1
        ];
        try{
            DB::beginTransaction(); 
            $userId = DB::table('users')->insertGetId($user);
            $ret = DB::table('personal')->where('id',$request->personal_id)->update(['user' => $userId ]);
            DB::commit();
            $response = [ 'status'=> true, 'data' => $ret];
            $codeResponse = 200;

        } catch(\Exception $e){
            DB::rollBack();
            $response = [ 'status'=> true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    public function changePassword(Request $request){
        try{
            $pass = hash('sha256', $request->one);
            $respuesta = DB::table('users')->where('id', $request->user_id)->update(['password' => $pass]);
            $response = [ 'status'=> true, 'data' => $respuesta];
            $codeResponse = 200;

        } catch(\Exception $e){
            $response = [ 'status'=> true, 'mensaje' => $e->getMessage(), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
}
