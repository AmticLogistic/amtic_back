<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif !important;
            text-align: justify;
            text-justify: inter-word;
        }

        .container {
            width: 90%;
            margin: 15px auto;
        }

        tr {
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .table {
            border-collapse: collapse;
        }

        .table th {

            border: 1px solid black;
        }

        .table td {
            border-collapse: collapse;
            border: 1px solid black;
        }

        @page {
            margin: 100px 25px;
        }

        header {
            position: fixed;
            top: -100px;
            left: -25px;
            right: -25px;
            height: 100px;
            /** Extra personal styles **/
            /* background-color: #29323b;
            color:#f2f6fa ; */
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -100px;
            left: -25px;
            right: -25px;
            height: 80px;
            font-size: 9px;
            /** Extra personal styles **/
            /* background-color: #29323b;
            color: #f2f6fa; */
        }

        .box {
            display: block;
            max-width: 210px;
            position: relative;
            border: 1px solid black;
            padding: 12px;
            text-align: center
        }
        .box2 {
            display: block;
            position: relative;
            border: 1px solid black;
            padding: 12px;
        }
        footer .pagenum:before {
            content: counter(page);
        }
    </style>
</head>

<body>
    <header>
        <table style="width:100%;border:none">
            <tr>
                <td width="150" style="text-align:center">
                    <img src="./img/logo.png" width="90" alt="" style="margin-top:10px">
                </td>
                <td style="text-align:left;padding:0px 50px 0px 0px;line-height:14px"> <br>
                    <span style="font-size: 14px;font-weight:bold;text-align: center;bottom: 10px;">TODO IMPORTS PERU
                        S.R.L.</span><br><br>
                    <span style="font-size: 9px">CALLE NUEVA URB. SAN FRANCISCO MZA. C4 LOTE. 11 MOQUEGUA - MARISCAL
                        NIETO - MOQUEGUA</span><br>
                    <span style="font-size: 11px"><b>Correo:</b>: todoimports.ventas@gmail.com</span><br>
                    <span style="font-size: 11px"><b>Central :</b>: 975 942 400 / 942 888 075</span><br>
                    <span style="font-size: 11px"><b>Servicio Tecnico :</b>: 968 779 399</span><br>
                </td>
                <td style="text-align:center">
                    <div class="box">
                        <strong>
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
                            FACTURA ELECTRONICA
                            @endif
                            @if($data->tipoDocumento_id == '03')
                            BOLETA ELECTRONICA
                            @endif
                        </strong>
                        <br><br>
                        <strong style="font-size: 12px;font-weight: bold;">{{ $data->serie }}-{{ $data->correlativo
                            }}</strong>
                    </div>

                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table style="width:90%;margin:10px auto">
            <tr>
                <td>Representación impresa del comprobante electrónico. Consúltela en https://facturaelectronica.claro.com.pe/plataforma/anonimus-consult &copy;</td>
            </tr>
            <td>
                <td>
                    <div class="pagenum-container">Pagina: <span class="pagenum"></span></div>
                </td>
            </td>
        </table>
    </footer>
    <table style="width:100%;margin:30px 0px">
        <tr>
            <th colspan="2" style="border-right:none" width="10">
                <p style="font-size:11px;padding:1px 8px;margin:0">Documento </p>
                <p style="font-size:11px;padding:1px 8px;margin:0">Cliente </p>
                <p style="font-size:11px;padding:1px 8px;margin:0">Dirección </p>
                <p style="font-size:11px;padding:1px 8px;margin:0">Nº O/C </p>
                <p style="font-size:11px;padding:1px 8px;margin:0">VENDEDOR </p>
                <p style="font-size:11px;padding:1px 8px;margin:0">OBSERVACIÓN </p>
            </th>
            <td colspan="4" style="border-left:none">
                <p style="font-size:11px;margin:0;padding:1px 0px">: &nbsp;&nbsp;{{$data->documento}} </p>
                <p style="font-size:11px;margin:0;padding:1px 0px">: &nbsp;&nbsp;{{ $data->nombres }} {{ $data->paterno }}</p>
                <p style="font-size:11px;margin:0;padding:1px 0px">: &nbsp;&nbsp;{{$data->dir}} </p>
                <p style="font-size:11px;margin:0;padding:1px 0px">: &nbsp;&nbsp;12164 </p>
                <p style="font-size:11px;margin:0;padding:1px 0px">: &nbsp;&nbsp;CAJA 01 </p>
                <p style="font-size:11px;margin:0;padding:1px 0px">: &nbsp;&nbsp;@if($data->nota)<span> {{$data->nota}}</span> @else <span>-</span> @endif </p>
            </td>
            <th colspan="2" style="border-right:none">
                <p style="font-size:11px;padding:1px 8px;margin:0">Tipo Moneda </p>
                <p style="font-size:11px;padding:1px 8px;margin:0">Fecha de Emisión</p>
                <p style="font-size:11px;padding:1px 8px;margin:0">Fecha de VCTO.</p>
                <p style="font-size:11px;padding:1px 8px;margin:0">COND. PAGO</p>
                <p style="font-size:11px;padding:1px 8px;margin:0">N° GUIA</p>
            </th>
            <td colspan="4" style="border-left:none">
                <p style="font-size:11px;margin:0;padding:1px 0px">: &nbsp;&nbsp;SOLES</p>
                <p style="font-size:11px;margin:0;padding:1px 0px">: &nbsp;&nbsp;{{ Carbon\Carbon::parse($data->fecha)->format('d/m/Y') }}</p>
                <p style="font-size:11px;margin:0;padding:1px 0px">: &nbsp;&nbsp;{{ Carbon\Carbon::parse($data->fecha)->addMonths(1)->format('d/m /Y') }}</p>
                <p style="font-size:11px;margin:0;padding:1px 0px">: &nbsp;&nbsp;CONTADO</p>
                <p style="font-size:11px;margin:0;padding:1px 0px">: &nbsp;&nbsp; -</p>
            </td>
        </tr>
    </table>
    <table class="table">
        <thead>
            <tr style="font-size:11px; background-color:#c9c9c9">
                <th width="30" style="text-align:center">CÓDIGO</th>
                <th colspan="6" style="padding-left:6px">DESCRIPCIÓN</th>
                <th width="42" style="text-align:center">U.M</th>
                <th width="30" style="text-align:center">CANT.</th>
                <th style="text-align:center" colspan="2">VALOR UNIT.</th>
                <th width="30" style="text-align:center">DT%</th>
                <th style="text-align:center" colspan="2">TOTAL</th>
            </tr>
        </thead>

        <tbody style="font-size:10px">
            @foreach($data->detalles as $s)
            <tr>
                <td style="text-align:center">{{$s->codigo}}</td>
                <td colspan="6"
                    style="text-align:left;font-size:10px;padding:4px 7px;font-family: Arial, Helvetica, sans-serif;text-transform: uppercase;">
                    {{ $s->nombre }}
                    @if( $s->series && count(json_decode($s->series)) > 0 )
                    <b>&nbsp;&nbsp;NS: </b>
                    @foreach (json_decode($s->series) as $se)
                    <b>{{ $se }}</b>
                    @endforeach
                    @endif
                </td>
                <td style="text-align:center">NIU</td>
                <td style="text-align:center">{{$s->cantidad}}</td>
                <td style="text-align:center" colspan="2" style="width:120px">{{ number_format($s->precio,2)}}</td>
                <td style="text-align:center">0.00</td>
                <td style="text-align:center" colspan="2">{{ number_format($s->subtotal,2)}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <table style="width:100%;margin:30px 0px; font-size:10px">
        <tr >
            <td colspan="12">
                <span class="box2">SON : {{ $data->montoLetras }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="width:120px">
                <b>Nro. de Cuenta de Detracción: </b>00-141-122703 <br>
                <b> N° CUENTA CORRIENTE SOLES BCP: </b>430-8962190-0-04 <br>
                <b> N° CUENTA INTERBANCARIA: </b>002-430-008962190004-75 <br>
            </td>
            <td colspan="3" style="text-align:center;padding:12px;width:90px">
                <img width="90" src="data:image/png;base64, {!! $qrcode !!}">
            </td>
            <td colspan="7">
                <div class="box2" style="line-height:8px">
                    <strong>OP. GRAVADAS : </strong><span style="float:right">{{ number_format($data->gravado,2) }}</span><br><br>
                    <strong>DESCUENTOS : </strong><span style="float:right">0.00</span><br><br>
                    <strong>IGV : </strong><span style="float:right">{{ number_format($data->igv,2) }}</span><br><br>
                    <strong>IMPORTE TOTAL : </strong><span style="float:right"> {{ number_format($data->monto,2) }}</span>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>