<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>comprobante</title>
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
                    <h3 style="font-size:12px;padding-bottom: 8px">{{ $data->razonSocial }}</h3>
                </th>
            </tr>
            <tr>
                <th colspan="8" style="font-size:9px">RUC : {{ $data->RUC }}</th>
            </tr>
            <tr>
                <th colspan="8" style="font-size:9px">{{ $data->direccion }}</th>
            </tr>
            <tr>
                <th colspan="8" style="font-size:9px">Tel: {{ $data->telefono }}</th> 
            </tr>
            <tr>
                <th colspan="8" style="padding-bottom: 6px">Email: {{ $data->correo }}</th>
            </tr>
            <tr>
                <th colspan="8" style="border-top: 2px black dashed;padding:6px 0px 0px 0px;font-size:13px;">
                @if($data->tipoDocumento_id == '20')
                    RESERVA
                @endif
                @if($data->tipoDocumento_id == '30')
                    PASE
                @endif
                @if($data->tipoDocumento_id == '10')
                    RECIBO
                @endif
                @if($data->tipoDocumento_id == '01')
                    FACTURA DE VENTA ELECTRONICA
                @endif
                @if($data->tipoDocumento_id == '03')
                    BOLETA DE VENTA ELECTRONICA
                @endif
                </th>
            </tr>
            <tr>
                <th colspan="8" style="padding:0px 0px 4px 0px;font-size:11px">{{ $data->serie }} - {{  str_pad(( $data->correlativo ), 4, "0", STR_PAD_LEFT) }}</th>            
            </tr>
            <tr>
                <th colspan="2" style="text-align:right">Emisión:</th>
                <th colspan="6" style="text-align:left"> {{  date("Y-m-d", strtotime($data->fecha))  }} </th>
            </tr>
            <tr>
                <th colspan="2" style="text-align:right">Vence:</th>
                <th colspan="6" style="text-align:left"> {{ Carbon\Carbon::parse($data->fecha)->addMonths(1)->format('Y-m-d') }} </th>
            </tr>
            <tr>
                <th colspan="2" style="text-align:right">Cliente:</th>
                <th colspan="6" style="text-align:left"> {{ $data->nombres }} {{ $data->paterno }}</th>
            </tr>
            <tr >
                @if($data->tipoDocumento_id == '01')
                <th colspan="2" style="text-align:right">RUC:</th>
                @else
                <th colspan="2" style="text-align:right">DNI:</th>
                @endif
                <th colspan="6" style="text-align:left;margin-bottom:15px"> {{ $data->documento }}</th>
            </tr>
            <tr >
                <th colspan="2" style="text-align:right">Direc.:</th>
                <th colspan="6" style="text-align:left;margin-bottom:15px"> {{ $data->dir }}</th>
            </tr>
            
           
            <tr >
                <th colspan="8" align="left"> &nbsp;&nbsp;Atendido:
                    <b style="font-size:8px">{{ $data->user }}</b>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="8" style="border-bottom: 1px black dashed;margin:8px 0px 0px 0px"><br></td>
            </tr>
            <tr>
                <td width="20">Cant.</td>
                <td colspan="5">Descripcion</td>
                <td width="25">Prec.</td>
                <td width="25">Imp.</td>
            </tr>
            <tr>
                <td colspan="8" style="border-top: 1px black dashed;padding:4px 0px 0px 0px"></td>
            </tr>
            @foreach ($data->detalles as $det)
            <tr>
                <td style="text-align:center">{{ $det->cantidad }}</td>
                <td colspan="5"> 
                    {{ $det->nombre }}
                    @if( $det->series && count(json_decode($det->series)) > 0)
                        <b>&nbsp;&nbsp;NS: </b>
                        @foreach (json_decode($det->series) as $se)
                            <b>{{ $se }}</b> 
                        @endforeach
                    @endif
                </td>
                <td style="text-align:center">{{ $det->precio }}</td>
                <td style="text-align:center">{{ $det->subtotal }}</td>
            </tr>
            @endforeach
            
            <tr>
                <td colspan="8" style="border-top: 1px black dashed;padding:4px 0px 0px 0px"></td>
            </tr>
           
            <tr>
                <td colspan="8" style="text-align:right">
                    <p>Gravada: S/ {{ number_format($data->gravado,2) }}</p>
                    <p>IGV(18.00%): S/ {{ number_format($data->igv,2) }}</p>
                    <p>Descuento Total: S/ {{ number_format($data->descuento,2) }}</p>
                    <p>Total Pagar: S/ {{ number_format($data->monto,2) }}</p>
                </td>
            </tr>
            </tr>
            
            <tr>
                <td><br><br></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align:center;">
                    <img width="50" src="data:image/png;base64, {!! $qrcode !!}">
                </td>
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