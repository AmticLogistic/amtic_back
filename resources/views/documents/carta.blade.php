<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta de Garantia</title>
    <style>
        * {
            color:#3f4257;
            text-align: justify;
            font-family: Arial, Helvetica, sans-serif;
        }
        @page {
                margin: 100px 25px;
        }
        td{
            font-size:13px;
        }
        body{
            background-image: url(./img/unnamed.jpg);
        }
        .row{
            text-align:center;
            background-color: #c4d7eb;
            font-family: Arial, Helvetica, sans-serif;
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
                    TODO IMPORTS PERÚ S.R.L. <br>
                    RUC 20607924466
                </td>
            </tr>
        </table>
       
        
    </header>

    <footer>
        <table style="width:80%;margin:10px auto">
            <tr>
                <td colspan="2"><b>Dirección:</b>  28 de julio, Calle Nueva Mz C4 – Lt11 San Francisco Moquegua – Mariscal Nieto – Moquegua</td>
            </tr>
            <tr>
                <td><b>Celular Principal:</b>  975 942 400</td>
                <td style="text-align:right"><b>Correo:</b>  todoimports.ventas@gmail.com</td>
            </tr>
            <tr>
                <td><b>Celular Secundario:</b>  942 888 075</td>
            </tr>
        </table>&copy;
    </footer>

    <table style="width:90%;margin:0px auto">
        <tr>
            <td colspan="4"><h3 style="text-align: center">Carta de Garantia</h3></td>
        </tr>
        <tr>
            <td colspan="4"><p style="text-align: right"><span>Moquegua {{ Carbon\Carbon::parse($data->fecha)->locale('es_PE')->isoFormat('dddd D MMMM YYYY') }}</p></td>
        </tr>
        <tr>
            <td colspan="1" style="width:50px">SEÑOR(ES)</td>
            <td colspan="3">: &nbsp;&nbsp;{{ $data->nombres }} {{ $data->paterno }}</td>
        </tr>
        <tr>
            <td colspan="1" >RUC</td>
            <td colspan="3">: &nbsp;&nbsp;{{$data->documento}}</td>
        </tr>
        <tr>
            <td colspan="1">FACTURA</td>
            <td colspan="3">: &nbsp;&nbsp;{{ $data->serie }}-{{ $data->correlativo }}</td>
        </tr>
        <tr>
            <td colspan="4">DESCRIPCION</td>
        </tr>
        <tr>
            <td colspan="4">
                <ul>
                    @foreach($data->detalles as $s)
                    <li>{{$s->cantidad}} {!! $s->nombre !!}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="row">POLITICAS DE GARANTIA</td>
        </tr>
        <tr>
            <td colspan="4">
                <ul>
                    <li>La garant&iacute;a se aplica solo al cliente {{ $data->nombres }} {{ $data->paterno }} CON
                        <strong>RUC
                        </strong>{{$data->documento}} de <strong>TODO IMPORTS PERU S.R.L., </strong>(representado por Edwin
                        Sadhams Roque
                        Rojas) identificado con <strong>DNI 47105656 </strong>y no se extiende a terceros.</li>
                    <li>Cada producto var&iacute;a sus t&eacute;rminos de Garant&iacute;a de acuerdo con las
                        pol&iacute;ticas
                        manejadas por el propio fabricante.</li>
                    <li>El horario de atenci&oacute;n es de lunes a s&aacute;bado de 9:00 AM a 9:00 PM (Horario
                        Corrido).</li>
                    <li>Para hacer efectiva la garant&iacute;a del producto el cliente debe traer su comprobante
                        (original o copia
                        de factura o boleta), no se recepcionara ning&uacute;n producto sin su respectivo comprobante.
                    </li>
                    <li>No est&aacute; incluido en la Garant&iacute;a los backups, copias de archivo y/o programas, los
                        mantenimientos generales, las reinstalaciones o restauraciones de software y fallas causadas por
                        virus, por
                        lo cual la empresa no se responsabiliza por la p&eacute;rdida parcial o total de la
                        informaci&oacute;n
                        almacenada en su equipo.</li>
                    <li>El producto debe ser remitido a nuestro centro de servicio debidamente protegido, en caso de
                        m&aacute;quinas
                        ensambladas, solo bastar&aacute; con traer el CPU, sin monitor ni teclado ni mouse ni cables,
                        conforme fue
                        despachado.</li>
                    <li>Los productos que ingresa por garant&iacute;a podr&aacute;n ser reparada o remanufacturada con
                        partes y
                        piezas nuevas y ser&aacute; garantizada por el periodo restante de garant&iacute;a.</li>
                    <li>El cliente recibir&aacute; una copia de la gu&iacute;a de recepci&oacute;n de los productos a
                        ser
                        internados.</li>
                    <li>Para preservar la seguridad de sus equipos, el recojo de los mismo solo se efectuar&aacute; con
                        el documento
                        original de este comprobante (gu&iacute;a de recepci&oacute;n)</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="row">EXCEPCIONES DE GARANT&Iacute;A</td>
        </tr>
        <tr>
            <td colspan="4">
                <ul>
                    <li>El producto que presente etiquetas del Fabricante o de <strong>TODO IMPORTS</strong> adulterados
                        o removidos
                        y que muestren evidencia de intento de reparaci&oacute;n, autom&aacute;ticamente perder&aacute;
                        la
                        garant&iacute;a.</li>
                    <li>En ning&uacute;n caso se recibir&aacute;n productos con da&ntilde;o f&iacute;sico alguno, ni
                        se&ntilde;ales
                        de haber sido manipulado o forzado, ni que presente alg&uacute;n componente quemado.</li>
                    <li>No se aceptan productos por error de compra o por cambio de modelo.</li>
                    <li>Los da&ntilde;os causados a los productos por fallas el&eacute;ctricas externas, sobrecargas,
                        mala
                        instalaci&oacute;n y falta de mantenimiento o la presencia de cualquier elemento extra&ntilde;o
                        (&aacute;cidos, l&iacute;quidos, qu&iacute;micos, insectos, presencia de &oacute;xido o
                        defecaci&oacute;n de
                        roedores) o uso inapropiado, no son cubiertos por la garant&iacute;a.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="row">PLAZOS DE GARANT&Iacute;A</td>
        </tr>
        <tr>
            <td colspan="4">
                <ul>
                    <li><strong>Equipos de Electricidad, Iluminaci&oacute;n y Electr&oacute;nica cuentan con 1
                            A&ntilde;o de
                            Garant&iacute;a (12 meses),</strong> si el producto presentase fallas de fabricaci&oacute;n
                        se
                        proceder&aacute; con el cambio y/o reposici&oacute;n inmediata en un plazo no mayor a 05
                        d&iacute;as
                        h&aacute;biles.</li>
                    <li>En caso de que el producto se encontrara descontinuado, se entregara un producto equivalente de
                        reemplazo o
                        se generara una nota de cr&eacute;dito equivalente al 100% del valor de compra dentro de los 12
                        meses de
                        Garant&iacute;a.</li>
                    <li>Todos los productos pasados los 12 meses de garant&iacute;a se debe tramitar directamente con
                        los CAS
                        autorizados y regirse seg&uacute;n a las condiciones o pol&iacute;ticas de Garant&iacute;a de
                        cada marca.
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="row">GARANTIA DIRECTA CON CENTROS AUTORIZADOS DE SERVICIO (C.A.S.)</td>
        </tr>
        <tr>
            <td colspan="4">
                <p>Los productos de las marcas <strong>ASUS, ACER, AOC, APC, BENQ, TEROS, CANON, DELL, D-LINK,
                    </strong><strong>ESET, HP, IBM, LENOVO, LG, FORZA, TP-LINK, MICROSOFT, WESTERN DIGITAL, CORSAIR,
                        KINGSTON,
                        VIEWSONIC, </strong><strong>WACOM, REDRAGON, GIGABYTE, BROTHER, EPSON, CANON, SAMSUNG, ADVANCE,
                        MSI.;</strong> la garant&iacute;a es directamente con el Centro Autorizados de Servicio (C.A.S.)
                    deben
                    presentar sus respectivos comprobantes de compra, as&iacute; como regirse a las condiciones de
                    garant&iacute;a
                    de cada marca, por lo cual no deben ser enviados a nuestro departamento de garant&iacute;as.</p>
                <p>&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="row">TERMINOS Y CONCEPTOS</td>
        </tr>
        <tr>
            <td colspan="4"> <br><br>
                <u> GARANTIA</u><br>
                <ul>
                    <li>La garant&iacute;a es un compromiso temporal que otorga el fabricante, la tienda o vendedor al
                        adquiriente
                        de un bien o servicio, por el cual se obliga a reparar de manera gratuita, en caso de
                        aver&iacute;a o
                        defecto de fabricaci&oacute;n.</li>
                    <li>Durante el periodo de su validez la ley protege al consumidor frente al mal funcionamiento que
                        pueda sufrir
                        los productos que hayan adquirido.</li>
                </ul>
                <u>**GARANTIA DE MARCA**</u><br>
                <ul>
                    <li>La garant&iacute;a de marca tiene un determinado plazo seg&uacute;n por tipo de productos y el
                        manejo de las
                        pol&iacute;ticas del fabricante (un mes, seis meses, un a&ntilde;o, etc.). Para hacer valido
                        esta
                        garant&iacute;a se debe ir directamente a los CAS Autorizados.</li>
                </ul>
                <u>CAS</u><br>
                <ul>
                    <li>Centro Autorizado de Servicio T&eacute;cnico, se encarga de fallas de fabricaci&oacute;n
                        reportado por
                        garant&iacute;a durante un periodo determinado.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td style="height:100px"></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center">
                ____________________ <br>
            </td>
        </tr>
    </table>

</body>

</html>