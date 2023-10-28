<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher Pago</title>
    <style>
        
       *{ 
           margin:0px;
           padding:0px;
           font-size:9px;
           font-family: 'Lucida', sans-serif;;
        }
       table{
           margin:15px auto 15px auto;
           width: 95%;
       }
    </style>
</head>
<body style="padding:0px 12px">
    <table>
        <thead>
            <tr>
                <th colspan="8">
                    <h3 style="font-size:12px;padding-bottom: 8px">{{ $empresas->razonSocial }}</h3>
                </th>
            </tr>
            <tr>
                <th colspan="8" style="font-size:9px">RUC : {{ $empresas->RUC }}</th>
            </tr>
            <tr>
                <th colspan="8" style="font-size:9px">{{ $empresas->direccion }}</th>
            </tr>
            <tr>
                <th colspan="8" style="font-size:9px">Tel: {{ $empresas->telefono }}</th> 
            </tr>
            <tr>
                <th colspan="8" style="padding-bottom: 6px">Email: {{ $empresas->correo }}</th>
            </tr>
            <tr>
                <th colspan="8" style="border-top: 2px black dashed;padding:6px 0px 0px 0px;font-size:13px;">
                    Voucher de Pago
                </th>
            </tr>
            <tr>
                <th colspan="8" style="padding:0px 0px 4px 0px;font-size:11px">VHP - {{  str_pad(( $pago->id ), 4, "0", STR_PAD_LEFT) }}</th>            
            </tr>
            <tr>
                <th colspan="2" style="text-align:right">Fecha: </th>
                <th colspan="6" style="text-align:left"> {{  date("Y-m-d", strtotime($pago->fecha))  }} </th>
            </tr>
            <tr>
                <th colspan="2" style="text-align:right">Cliente:</th>
                <th colspan="6" style="text-align:left"> {{ $persona->nombres }} {{ $persona->paterno }}</th>
            </tr>
            <tr >
                <th colspan="2" style="text-align:right">DNI:</th>
                <th colspan="6" style="text-align:left;margin-bottom:15px"> {{ $persona->documento }}</th>
            </tr>
            <tr >
                <th colspan="2" style="text-align:right">Direc.:</th>
                <th colspan="6" style="text-align:left;margin-bottom:15px"> {{ $persona->direccion }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="8" style="border-bottom: 1px black dashed;margin:8px 0px 0px 0px"><br></td>
            </tr>
            <tr>
                <td width="20">Cant.</td>
                <td colspan="5">Descripcion</td>
                <td width="25">Monto</td>
            </tr>
            <tr>
                <td colspan="8" style="border-top: 1px black dashed;padding:4px 0px 0px 0px"></td>
            </tr>
            
            <tr>
                <td style="text-align:center">1</td>
                <td colspan="5"> Pago de credito</td>
                <td style="text-align:center">{{ $pago->monto }}</td>
            </tr>
            
            <tr>
                <td colspan="8" style="border-top: 1px black dashed;padding:4px 0px 0px 0px"></td>
            </tr>
           
            <tr>
                <td colspan="8" style="text-align:right">
                    <p>Monto Pagado: S/ {{ number_format($pago->monto ,2) }}</p>
                    <p>Total Deuda: S/ {{ number_format($resta ,2) }}</p>
                </td>
            </tr>
            </tr>
            
            <tr>
                <td><br><br></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align:center;padding-top:12px">
                    <b>Gracias por su preferencia.</b>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>