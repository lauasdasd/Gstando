<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carátula de Préstamo - Solicitud <?= htmlspecialchars($prestamo['numero_solicitud']) ?></title>
    <style>

    @page { size: A4; margin: 0; }
    body { 
        font-family: 'Times New Roman', Times, serif; 
        font-size: 11pt; /* Reducido para que quepa más contenido */
        margin: 0; 
        padding: 0; 
    }
    .page { 
        width: 210mm; 
        height: 297mm; 
        padding: 10mm 15mm; /* Márgenes reducidos */
        box-sizing: border-box; 
        display: flex; 
        flex-direction: column; 
    }
    .page-break { page-break-after: always; }
    .text-center { text-align: center; }
    .title {
        font-weight: bold;
        font-size: 1.2em; /* Título principal un poco más pequeño */
        margin-bottom: 3mm;
    }
    table { width: 100%; border-collapse: collapse; }
    td { 
        padding: 1.5mm 2mm; /* Relleno de celdas reducido */
        vertical-align: top; 
    }
   #page-2 table tr {
    border-bottom: 1px solid #ccc; /* Agrega un borde de 1px, sólido y de color gris claro */
}
    .label {
        font-weight: bold;
        color: #333;
        padding-right: 5mm;
        white-space: nowrap;
    }
    .underline { border-bottom: 1px solid black; }
    .border { border: 1px solid black; }
    .box { 
        border: 1px solid black; 
        padding: 5mm; 
        margin-top: 5mm; /* Margen superior reducido */
    }
    .small-text { font-size: 0.9em; }
    hr {
    display: block;
    margin-block-start: 0.3em;
    margin-block-end: 0.3em;
    margin-inline-start: auto;
    margin-inline-end: auto;
    unicode-bidi: isolate;
    overflow: hidden;
    border-style: inset;
    border-width: 1px;
}
        .second-page-margins {
        padding: 20mm; /* Puedes ajustar este valor para más o menos margen */
    }
    @media print {
        body { -webkit-print-color-adjust: exact; }
        .no-print { display: none; }
    }
</style>
    
</head>
<body onload="window.print();">

<div class="page">
    <div class="text-center">
        <h1 class="title">DECLARACION JURADA DE DATOS P/PRESTAMOS BANCARIZADOS</h1>
    </div>
    
    <div style="margin-bottom: 10mm;">
        <table>
            <tr>
                <td class="label">APELLIDO Y NOMBRES:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['apellido']) . ' ' . htmlspecialchars($prestamo['nombre']) ?></td>
            </tr>
            <tr>
                <td class="label">DNI:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['dni']) ?></td>
            </tr>
            <tr>
                <td class="label">CUIL:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['cuil']) ?></td>
            </tr>
            <tr>
                <td class="label">LUGAR Y FECHA NACIMIENTO:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['lugar_nacimiento']) ?></td>
            </tr>
            <tr>
                <td class="label">NACIONALIDAD:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['nacionalidad']) ?></td>
            </tr>
            <tr>
                <td class="label">PROFESION - ACTIVIDAD:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['profesion']) ?></td>
            </tr>
            <tr>
                <td class="label">DOMICILIO PARTICULAR:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['domicilio']) ?></td>
            </tr>
            <tr>
                <td class="label">LOCALIDAD:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['localidad']) ?></td>
            </tr>
            <tr>
                <td class="label">CODIGO POSTAL:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['codigo_postal']) ?></td>
            </tr>
            <tr>
                <td class="label">CELULAR:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['telefono']) ?></td>
            </tr>
            <tr>
                <td class="label">ESTADO CIVIL:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['estado_civil']) ?></td>
            </tr>
            <tr>
                <td class="label">SEXO:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['sexo']) ?></td>
            </tr>
            <tr>
                <td class="label">SEGURO:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['seguro']) ?></td>
            </tr>
        </table>
    </div>
    
    <hr style="margin: 1mm 0;">
    
    <div>
        <table>
            <tr>
                <td class="label">N° DE SOLICITUD:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['numero_solicitud']) ?></td>
            </tr>
            <tr>
                <td class="label">IMPORTE DEL PRESTAMO (EN NUMEROS):</td>
                <td class="underline">$<?= number_format($prestamo['importe'], 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td class="label">IMPORTE DEL PRESTAMO (EN LETRAS):</td>
                <td class="underline"><?= htmlspecialchars($prestamo['importe_letras']) ?></td>
            </tr>
            <tr>
                <td class="label">PLAZO:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['numero_cuotas']) ?></td>
            </tr>
            <tr>
                <td class="label">CAJA DE AHORRO:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['caja_ahorro']) ?></td>
            </tr>
            <tr>
                <td class="label">FECHA DE SOLICITUD:</td>
                <td class="underline"><?= date('d/m/Y', strtotime(htmlspecialchars($prestamo['fecha_solicitud']))) ?></td>
            </tr>
            <tr>
                <td class="label">REPARTICION:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['reparticion']) ?></td>
            </tr>
            <tr>
                <td class="label">LINEA DE CREDITOS:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['linea_credito']) ?></td>
            </tr>
            <tr>
                <td class="label">DESTINO DEL PRESTAMO:</td>
                <td class="underline"><?= htmlspecialchars($prestamo['destino_prestamo']) ?></td>
            </tr>
        </table>
    </div>
    
    <div class="box">
        <div class="text-center title">Certificaciones de Deuda:</div>
        <div class="border" style="padding: 15mm;"></div>
    </div>
</div>

<div class="page-break"></div>

<div class="page" id="page-2" style="justify-content: center; align-content:center">
    <div style="border: solid 1px; padding: 30px" >
        <div style="margin: 20px">

            <div class="text-center title" style="text-decoration: underline;"><h1>PRESTAMOS PERSONALES EMPLEADOS PUBLICOS</h1></div>
            
            <div class="text-center" style="margin-top: 10mm;">
                <h1>LINEA: <span class="underline"><?= htmlspecialchars($prestamo['linea_credito']) ?></span></h1>
                <h1><span class="underline"><?= htmlspecialchars($prestamo['apellido']) . ' ' . htmlspecialchars($prestamo['nombre']) ?></span></h1>
                <h2><span class="underline"><?= htmlspecialchars($prestamo['cuil']) ?></span></h2>
                <h2>C. De Ahorros: <span class="underline"><?= htmlspecialchars($prestamo['caja_ahorro']) ?></span></h2>
            </div>
            
            <div class="box">
                <div class="text-center title">Con Certificaciones de Deuda:</div>
                <div class="border" style="padding: 15mm;"></div>
            </div>
            
            <div style="text-align: right; margin-top: 10mm;">
                <p class="underline">SUCURSAL SAENZ PEÑA</p>
            </div>
        
        <div class="box small-text" style="margin-top: 5mm;">
            <div class="text-center title">DATOS DE INTERES:</div>
            <table>
                <tr>
                    <td class="label">FECHA ENVIO:</td>
                    <td><?= date('d/m/Y', strtotime(htmlspecialchars($prestamo['fecha_solicitud']))) ?></td>
                </tr>
                <tr>
                    <td class="label">MONTO:</td>
                    <td>$<?= number_format($prestamo['importe'], 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td class="label">SOLICITUD N°:</td>
                    <td><?= htmlspecialchars($prestamo['numero_solicitud']) ?></td>
                </tr>
                <tr>
                    <td class="label">SEGURO:</td>
                    <td><?= htmlspecialchars($prestamo['seguro']) ?></td>
                </tr>
                <tr>
                    <td class="label">ATENDIÓ:</td>
                    <td>
                        <?= htmlspecialchars($prestamo['nombre_completo'])?>
                    </td>
                </tr>
            </table>
        </div>
        </div>
    </div>
</div>

</body>
</html>