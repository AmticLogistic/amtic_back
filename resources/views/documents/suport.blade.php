<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta de Garantia</title>
    <style>
        * {
            color: #3f4257;
            text-align: justify;
            font-family: Arial, Helvetica, sans-serif;
        }

        @page {
            margin: 100px 25px;
        }

        td {
            font-size: 13px;
        }

        body {
            background-image: url(./img/unnamed.jpg);
        }

        td {
            padding: 8px 8px;
            font-size: 11px;
            line-height: 18px
        }

        .border {
            border: 1px solid black;
        }

        header {
            position: fixed;
            top: -100px;
            left: -25px;
            right: -25px;
            height: 100px;
            /** Extra personal styles **/
            background-color: #eef6ff;
            color: white;
            text-align: center;
            line-height: 35px;
        }

        footer {
            position: fixed;
            bottom: -100px;
            left: -25px;
            right: -25px;
            height: 80px;
            font-size: 9px;
            /** Extra personal styles **/
            background-color: #eef6ff;
            color: white;
        }
        
    </style>
</head>

<body>
    <!-- Define header and footer blocks before your content -->
    <header>
        <table style="width:100%">
            <tr>
                <td width="150" style="text-align:center">
                    <img src="./img/logo.png" width="80" alt="" style="margin-top:10px">
                </td>
                <td style="text-align:right;padding:0px 50px 0px 0px"> 
                   
                </td>
            </tr>
        </table> 
    </header>

    <footer>
        <table style="width:80%;margin:10px auto">
            <tr>
                <td style="padding:0px" colspan="2" ><b>Dirección:</b>  28 de julio, Calle Nueva Mz C4 – Lt11 San Francisco Moquegua – Mariscal Nieto – Moquegua</td>
            </tr>
            <tr>
                <td style="padding:0px"><b>Celular Principal:</b>  975 942 400</td>
                <td style="padding:0px" style="text-align:right"><b>Correo:</b>  todoimports.ventas@gmail.com</td>
            </tr>
            <tr>
                <td style="padding:0px"><b>Celular Secundario:</b>  942 888 075</td>
            </tr>
        </table>&copy;
    </footer>
    <table style="width: 95%; border-collapse: collapse;margin:10px auto">
        <tr>
            <td colspan="12" style="text-align: center;">
                <img src="./img/portada.png" width="520" alt="">
                <h2 style="text-align:center">ORDEN DE SERVICIO</h2>
                
            </td>
        </tr>
        <tr>
            <td colspan="12" style="text-align:right">
              <b>Codigo: SP-{{ str_pad( $data->id , 4, '0', STR_PAD_LEFT) }}</b>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:center;background-color:#DDD9C3" class="border">
                <b>DATOS DEL CLIENTE</b>
            </td>
            <td colspan="6" style="text-align:center;background-color:#DDD9C3" class="border">
                <b>DATOS DEL EQUIPO</b>
            </td>
        </tr>
        <tr>
            <td colspan="6" class="border">
                <b> CLIENTE:</b> {{ $data->nombres }} {{ $data->paterno }}<br>
                <b> RUC/DNI:</b> {{ $data->documento }}<br>
                <b> CELULAR:</b> {{ $data->telefono }}<br>
                <b> N° DE FACTURA/ BOLETA:</b> {{ $data->numero }} <br>
                <b> MONTO DEL PRODUCTO:</b> {{ $data->monto }} <br>
                <b> DIRECCION:</b> {{ $data->direccion }} <br>
            </td>
            <td colspan="6" class="border">
                <b> MARCA:</b> {{ $data->marca }}<br>
                <b> MODELO:</b> {{ $data->modelo }}<br>
                <b> N° SERIE:</b> {{ $data->serie }}<br>
                <b> FECHA DE INGRESO:</b> {{ date('d/m/Y', strtotime($data->fechaIngreso))  }}<br>
                <b> HORA DE INGRESO:</b> {{ date('h:m a', strtotime($data->fechaIngreso)) }}<br>
            </td>
        </tr>
        <tr>
            <td colspan="12" class="border" style="background:#DDD9C3">
                <b>DESCRIPCION DEL ESTADO GENERAL:</b>  {{ $data->descripcion }}
            </td>
        </tr>
        <tr>
                <td colspan="12" class="border">
                    
                    @foreach($data->box as  $op)
                    <label style="margin-right:50px">
                        <input type="checkbox" style="margin-top:5px" @if( $op->status == true ) checked @endif>&nbsp;
                        <span style="margin-top:-10px">{{ $op->name }}</span>
                    </label>
                    @endforeach 
                </td>
            </tr>
        <tr>
            <td colspan="12" class="border">
                <b>CARACTERISTICAS DEL EQUIPO: </b> {{ $data->caracteristicas }}
            </td>
        </tr>
        <tr>
            <td colspan="12" class="border">
                <b>COMENTARIOS: </b>  {{ $data->comentarios }}
            </td>
        </tr>
        <tr>
            <td colspan="12" class="border">
                <b>DESPERFECTO: </b> {{ $data->desperfecto }}
            </td>
        </tr>

        <tr>
            <td colspan="6" style="text-align:center">
                <br><br><br><br><br>________________________________ <br> TODO IMPORTS PERU S.R.L <br> RUC 20607924466
            </td>
            <td colspan="6" style="text-align:center">
                <br><br><br><br><br>________________________________ <br> FIRMA CONFORME
            </td>
        </tr>
        <tr>
            <td colspan="12" class="border">
                <b>OBSERVACIÓN: </b> {{ $data->observacion }}
            </td>
        </tr>
        </tr>
    </table>
</body>

</html>