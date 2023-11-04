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
            font-size: 9px;
            border: 1px solid black;
        }

        .table td {
            font-size: 9px;
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
        .borderb{
            border-bottom: 1px solid black;
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
                    <img src="./img/logo_app.png" width="90" alt="" style="margin-top:10px">
                    <p style="font-size: 9px;font-weight:bold;text-align: center;">SERVICIOS GENERALES <br> AMARU DEL SUR S.A.C</p>
                    <span style="font-size: 7px">RUC: 20608024167</span>
                </td>
                <td style="text-align:left;padding:0px 50px 0px 0px;line-height:14px"> <br>
                    <h3>KARDEX FISICO</h3>
                    <span style="font-size:11px">PROYECTO EL VALLE</span> 
                    <span style="font-size:9px">Del 2022-01-01 Al 2022-12-31</span>
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table style="width:90%;margin:10px auto">
            <tr>
                <td>Reporte generado por el sistema AMTIC.</td>
            </tr>
            <td>
            <td>
                <div class="pagenum-container">Pagina: <span class="pagenum"></span></div>
            </td>
            </td>
        </table>
    </footer>
    <?php
    $currentMaterial = null; // Material actual
    ?>
    @foreach ($data as $row)
    @if ($currentMaterial !== $row->material)
    @if ($currentMaterial !== null)
    </table> <!-- Cerrar tabla anterior si no es la primera -->
    @endif
    <table style="width:100%;margin:40px 0px 0px 0px;font-size:9px">
        <thead>
            <tr>
                <th colspan="8">{{ $row->material }}</th>
            </tr>
            <tr>
                <th class="borderb">Fecha</th>
                <th class="borderb">Tipo de Doc.</th>
                <th class="borderb"># Doc.</th>
                <th class="borderb">Concepto</th>
                <th class="borderb">RUC/DNI</th>
                <th class="borderb">Entrada</th>
                <th class="borderb">Salida</th>
                <th class="borderb">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @endif
            <tr>
                <td>{{ $row->fecha }}</td>
                <td>{{ $row->tipoDocumento }}</td>
                <td>{{ $row->nDocumento }}</td>
                <td>{{ $row->material }}</td>
                <td>{{ $row->doc }}</td>
                <td>{{ $row->entrada }}</td>
                <td>{{ $row->salida }}</td>
                <td>{{ $row->saldo }}</td>
            </tr>
            <?php $currentMaterial = $row->material; ?>
            @endforeach
    </table> <!-- Cerrar la última tabla -->
</body>

</html>