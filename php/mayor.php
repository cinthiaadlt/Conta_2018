<?php
if(!isset($conexion)){ include("conexion.php");}
$sql = "SELECT DISTINCTROW(cuenta) cuentas FROM registro";
$ejecutar_consulta = $conexion->query($sql);
if(isset($_POST['create_pdf'])){
  include("funciones.php"); 
  include('../tcpdf/tcpdf.php');
    
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Contabilidad vicaria');
    $pdf->SetTitle($_POST['reporte_name']);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(20, 20, 20, false);
    $pdf->SetAutoPageBreak(true, 20);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->addPage();
    $content = '';
    $content .= '
    <div class="container" id="contenido">
        <div class="row row-offcanvas row-offcanvas-right">
            <div class="col-xs-12 col-sm-9">
                  <h3 style="text-align:center;">LIBRO MAYOR</h3>
                        <p align="center">';
                            $fecha_ac = actual_date();  
                             $content .= '
                             '.$fecha_ac.'
                        </p>
                <div class="col-lg-12">
                        <table class="table table-condensed table-bordered table-striped" border="1" cellpadding="5" width="100%">
                            <thead bgcolor="">
                                <tr>
                                    <th align="center"  width="70%"><strong>CUENTA</strong></th>
                                    <th align="center"  width="15%"><strong>DEBE</strong></th>
                                    <th align="center"  width="15%"><strong>HABER</strong></th>
                                </tr>
                            </thead>
    ';
                            while($registro = $ejecutar_consulta->fetch_assoc()){
                                actualizarCuentas($conexion, $registro["cuentas"]);
                            }
                            $consulta = "SELECT DISTINCT(c.codigo_cuenta),c.subgrupo,SUM((c.saldo_debe)) sumdebe,SUM((c.saldo_haber)) sumhaber FROM cuentas c,subgrupos s WHERE c.subgrupo=s.codigo_subgrupo GROUP by c.subgrupo";
                            $consulta = $conexion->query($consulta);
                            while ($subg = $consulta->fetch_assoc()) {
                                $sql = "SELECT * FROM cuentas where subgrupo='".$subg["subgrupo"]."' ";
                                $ejecutar_consulta = $conexion->query($sql);
                                while($regs = $ejecutar_consulta->fetch_assoc()){
                                    if ($regs["subgrupo"]=$subg["subgrupo"]) {
                                        $c=substr($regs["subgrupo"],0,1);//estrae el primer nuemro de los subgrupos
                                        //una vez extraido el primer numero se elige un color segun la clase
                                        switch ($c) {
                                            case '1':
                                                $color='#B9EEAB';// verde
                                                break;
                                            case '2':
                                               $color='#FFE3A9'; //amarillo
                                                break;
                                            case '3':
                                                $color='#CFA5F7';//morado
                                                break;
                                            case '4':
                                                $color='#BBDDFB';//celeste
                                                break;
                                            case '5':
                                                $color='#FFC58C';//naranja
                                                break;
                                            case '6':
                                                $color='#E59866';// cafe
                                                break;
                                            case '7':
                                                $color='#F5B7B1';// coral
                                                break;
                                            case '8':
                                                $color='#B1F5D9';//cyan
                                                break;
                                            case '9':
                                                $color='#';
                                                break;
                                        }
                                       
                                        $content.=' 
                                        <tr bgcolor="'.$color.'">
                                            <td width="70%">'.$regs['codigo_cuenta'].' -   '.utf8_encode($regs['nombre_cuenta']).'</td>
                                            <td align="center" width="15%">'.number_format($regs['saldo_debe'],2).'</td>
                                            <td align="center" width="15%">'.number_format($regs['saldo_haber'],2).'</td>
                                        </tr>
                                        ';
                                    }
                                }            
                            $content.='
                                <tr bgcolor="#D7DBDD" align="center">
                                    <td width="70%" align="center"><strong nth>Sumas Totales:</strong></td>
                                    <td width="15%" align="center">'.number_format($subg['sumdebe'],2).'</td>
                                    <td width="15%"align="center">'.number_format($subg['sumhaber'],2).'</td>
                                </tr>             
                            ';
                            }
                            $sql = "SELECT SUM(saldo_debe) sumadebe, SUM(saldo_haber) sumahaber FROM cuentas";
                            $ejecutar = $conexion->query($sql);
                        while($reg = $ejecutar->fetch_assoc()){
                            if($reg["sumadebe"]!=$reg["sumahaber"]){
                                $color= '#D70000';
                                $content.='<tr bgcolor="'.$color.'">';
                                $content.='
                                <td align="center"><strong> <font color="#FFFFFF"> TOTAL FINAL </font> </strong> </td>
                                <td align="center"><strong><font color="#FFFFFF"> '.number_format($reg['sumadebe'],2).'</font> </strong></td>
                                <td align="center"><strong><font color="#FFFFFF"> '.number_format($reg['sumahaber'],2).'</font> </strong></td>
                                ';
                            }else {
                                $color= '#FFFFFF';
                                $content.='<tr bgcolor="'.$color.'">';
                                $content.='
                                    <td align="center" width="70%"><strong>TOTAL FINAL:</strong></td>
                                    <td align="center" width="15%"><strong>'.number_format($reg['sumadebe'],2).'</strong></td>
                                    <td align="center" width="15%"><strong>'.number_format($reg['sumahaber'],2).'</strong></td>
                                ';
                            }
                        }
                        $content.='</tr>';
                        $content.='
                        </table>

                       
                </div>
            </div>
        </div>
    </div>
                        ';
    $pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);
    ob_end_clean();
    $pdf->output('Reporte.pdf', 'I');
}
?>



<!--CONTENIDO DE LA PAGINA--->
<?php 
    include("sesion.php");
    if(!$_COOKIE["sesion"]){
        header("Location: salir.php");
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css"/>
    <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
    <script>
        !window.jQuery && document.write("<script src='../js/jquery.min.js'><\/script>");
    </script>
    <title>Libro Mayor</title>
</head>
<body>
    <!-- Barra de navegación -->
    <?php include("nav.php"); ?>
    <?php include("funciones.php"); ?>
    <?php 
    if(!isset($conexion)){ include("conexion.php");}
    $sql = "SELECT * FROM cuentas";
    $ejecutar_consulta = $conexion->query($sql);
    while($regs = $ejecutar_consulta->fetch_assoc()){
        actualizarCuentas($conexion, $regs["codigo_cuenta"]);
        saldosCuentas($conexion, $regs["codigo_cuenta"]);
    }
    ?>

    <!-- Contenido de la página -->
    <div class="container" id="contenido">
        <div class="row row-offcanvas row-offcanvas-right">
            <div class="col-xs-12 col-sm-9">
                <div class="page-header">
                    <?php $h1 = "Libro Mayor";
                        echo '<h3>'.$h1.'</h3>'
                    ?>
                </div>
                <div class="row">
                    <div class="col-lg-12 well">
                        <p align="justify" class="text-info">
                            Aquí se muestran los saldos de todas las cuentas registradas en el sistema. No aparecen las subcuentas puesto que sus montos totales se ven reflejados en las cuentas a las que pertenecen.
                        </p>
                    </div>
                    <hr>

                    <div class="col-lg-12">
                        <table class="table table-condensed table-bordered table-striped">
                            <thead class="justify">
                                <tr>
                                    <th>CUENTA</th>
                                    <th width="100"class="justify">DEBE</th>
                                    <th width="100" class="center">HABER</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(!isset($conexion)){
                                    include("conexion.php");
                                }
                                $sql = "SELECT DISTINCTROW(cuenta) cuentas FROM registro";
                                $ejecutar_consulta = $conexion->query($sql);
                                while($registro = $ejecutar_consulta->fetch_assoc()){
                                    actualizarCuentas($conexion, $registro["cuentas"]);
                                }
                                $consulta = "SELECT DISTINCT(c.codigo_cuenta),c.subgrupo,SUM((c.saldo_debe)) sumdebe,SUM((c.saldo_haber)) sumhaber FROM cuentas c,subgrupos s WHERE c.subgrupo=s.codigo_subgrupo GROUP by c.subgrupo";
                                $consulta = $conexion->query($consulta);
                                            while ($subg = $consulta->fetch_assoc()) {
                                                $sql = "SELECT * FROM cuentas where subgrupo='".$subg["subgrupo"]."' ";
                                                $ejecutar_consulta = $conexion->query($sql);
                                                while($regs = $ejecutar_consulta->fetch_assoc()){
                                                    if ($regs["subgrupo"]=$subg["subgrupo"]) {
                                                    echo "<tr>";
                                                    echo "<td>".$regs["codigo_cuenta"]." - ".utf8_encode($regs["nombre_cuenta"])."</td>";
                                                    echo "<td align='center'>".number_format($regs["saldo_debe"],2)."</td>";
                                                    echo "<td align='center'>".number_format($regs["saldo_haber"],2)."</td>";
                                                    echo "</tr>";
                                                    }
                                                }
                                                
                                                
                                                    echo "<tr> ";
                                                    echo "<td class='text-left'><strong>Sumas Totales:</strong></td>";
                                                    echo "<td align='center'><strong>".number_format($subg["sumdebe"],2)."</strong></td>";
                                                    echo "<td align='center'><strong>".number_format($subg["sumhaber"],2)."</strong></td>";
                                                    
                                                    echo "</tr>";
                                            }
                                            $sql = "SELECT SUM(saldo_debe) sumadebe, SUM(saldo_haber) sumahaber FROM cuentas";
                                    $ejecutar = $conexion->query($sql);
                                    echo "<tr class='alert alert-info' >";
                        while($reg = $ejecutar->fetch_assoc()){
                            if($reg["sumadebe"]!=$reg["sumahaber"]){
                                echo "<td> <strong>Totales:</strong> </td>";
                                echo "<td align='center'><strong>".number_format($reg["sumadebe"],2)."</strong></td>";
                                echo "<td align='center'><strong>".number_format($reg["sumahaber"],2)."</strong></td>";
                            } else {
                                echo "<td align='center'><strong>Totales:</strong> </td>";
                                echo "<td align='center' ><strong>".number_format($reg["sumadebe"],2)."</strong></td>";
                                echo "<td align='center'><strong>".number_format($reg["sumahaber"],2)."</strong></td>";
                            }
                            
                        }
                                    echo "</tr>";
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <div class="col-md-12">
                <form method="post">
                    <input type="hidden" name="reporte_name" value="<?php echo $h1; ?>">
                    <input type="submit" name="create_pdf" class="btn btn-danger pull-right" value="Generar PDF">
                </form>
              </div>
            </div><!--/span-->

            <!-- Barra lateral o sidebar -->
            <?php include("sidebar.php"); ?>
            
        </div>
    </div>

    <!-- Pie de página o Footer -->
    <?php include("footer.php"); ?>

    <!-- Ventanas flotantes -->
    <?php include("modal.php"); ?>

    <script src="../js/bootstrap.min.js"></script>
</body>
</html>