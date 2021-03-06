<?php
if(!isset($conexion)){
    include("conexion.php");
    /*consulata para obtenr totales segun subgrupo*/
    $consulta = "SELECT DISTINCT(c.codigo_cuenta),c.subgrupo,SUM((c.saldo_debe)) sumdebe,SUM((c.saldo_haber)) sumhaber FROM cuentas c,subgrupos s WHERE c.subgrupo=s.codigo_subgrupo GROUP by c.subgrupo";
                                    $consulta = $conexion->query($consulta);
if(isset($_POST['create_pdf'])){
  include('../tcpdf/tcpdf.php');
  include('funciones.php');
    
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
     $content .= '
    <div class="container" id="contenido">
        <div class="row row-offcanvas row-offcanvas-right">
            <div class="col-xs-12 col-sm-9">
                  <h3 style="text-align:center;">BALANCE DE COMPROBACION</h3>
                  <p align="center">';
                            $fecha_ac = actual_date();  
                             $content .= '
                             '.$fecha_ac.'
                        </p>
                <div class="col-lg-12">
                        <table class="table table-condensed table-bordered table-striped" border="1" cellpadding="5">
                            <thead >
                                <tr>
                                    <th align="center"><strong>CUENTA</strong></th>
                                    <th align="center"><strong>DEBE</strong></th>
                                    <th align="center"><strong>HABER</strong></th>
                                    <th align="center"><strong>SALDO DEBE</strong></th>
                                    <th align="center"><strong>SALDO HABER</strong></th>
                                </tr>
                            </thead>
    ';



                                $total_a=0;
                                $total_d=0;
                            /*Suma segun subgurupos de cuentas*/
            while ($subg = $consulta->fetch_assoc()) {
                $sql = "SELECT * FROM cuentas where subgrupo='".$subg["subgrupo"]."' ";
                $ejecutar = $conexion->query($sql);
                $deudor=0;
                $acreedor=0;
                $sum_a=0;
                $sum_d=0;
                while($regs = $ejecutar->fetch_assoc()){
                    $content.='
                        <tr>
                        <td>'.utf8_encode($regs["codigo_cuenta"]).''.utf8_encode($regs["nombre_cuenta"]).'</td>
                    ';
                    if($regs["saldo_debe"]==0){
                        $deudor = 0; 
                        $acreedor=$regs["saldo_haber"]-$regs["saldo_debe"];
                        $sum_d=$sum_d+$deudor;
                        $sum_a=$sum_a+$acreedor;
                        $content.='
                        <td class="text-right">'.number_format($regs["saldo_debe"],2).'</td>
                        <td class="text-right">'.number_format($regs["saldo_haber"],2).'</td>
                        <td align="right">$'.number_format($deudor, 2).'</td>
                        <td align="right">$'.number_format($acreedor, 2).'</td>
                        ';
                    }elseif ($regs["saldo_haber"]==0){
                        $deudor =$regs["saldo_debe"]-$regs["saldo_haber"]; 
                        $acreedor=0;
                        $sum_d=$sum_d+$deudor;
                        $sum_a=$sum_a+$acreedor;
                        $content.='
                        <td class="text-right">'.number_format($regs["saldo_debe"],2).'</td>
                        <td class="text-right">'.number_format($regs["saldo_haber"],2).'</td>
                        <td align="right">$ '.number_format($deudor, 2).'</td>
                        <td align="right">$ '.number_format($acreedor, 2).'</td>
                        ';
                    }elseif ($regs["saldo_debe"]<$regs["saldo_haber"]) {
                            $deudor = 0; 
                            $acreedor=$regs["saldo_haber"] - $regs["saldo_debe"];
                            $sum_d=$sum_d+$deudor;
                            $sum_a=$sum_a+$acreedor;
                            $content.=' 
                            <td class="text-right">'.number_format($regs["saldo_debe"],2).'</td>
                            <td class="text-right">'.number_format($regs["saldo_haber"],2).'</td>
                            <td align="right">$'.number_format($deudor, 2).'</td>
                            <td align="right">$'.number_format($acreedor, 2).'</td>
                            ';
                    }elseif ($regs["saldo_debe"]>$regs["saldo_haber"]) {
                            $deudor = $regs["saldo_debe"]-$regs["saldo_haber"];
                            $acreedor = 0;
                            $sum_d=$sum_d+$deudor;
                            $sum_a=$sum_a+$acreedor;
                            $content.=' 
                            <td class="text-right">'.number_format($regs["saldo_debe"],2).'</td>
                            <td class="text-right">'.number_format($regs["saldo_haber"],2).'</td>
                            <td align="right">$'.number_format($deudor, 2).'</td>
                            <td align="right">$'.number_format($acreedor, 2).'</td>
                                            ';
                        }    
                    
                                            $total_a=$total_a+$sum_a;
                                            $total_d=$total_d+$sum_d;

                    $content.='</tr>';
                    }
                                    $content.='<tr bgcolor="#A4A4A4">
                                        <td align="right" colspan="3"><strong>Sumas Totales:</strong></td>
                                        <td align="right">'.number_format($sum_d,2).'</td>
                                        <td align="right">'.number_format($sum_a,2).'</td>
                                    </tr>';
                                }
                                    /*Total de todas las cuentas*/ 
                                    $sql = "SELECT SUM(saldo_debe) as sumadebe, SUM(saldo_haber) as sumahaber FROM cuentas";
                                    $ejecutar = $conexion->query($sql);
                                    $content.='<tr bgcolor="#a5f5f5">';
                                    while($reg = $ejecutar->fetch_assoc()){
                                            
                                        if($reg["sumadebe"]!=$reg["sumahaber"]){
                                            $content.='<td class="danger"><strong>Totales:</strong> </td>
                                            <td class="text-right danger"><strong>'.number_format($reg["sumadebe"],2).'</strong></td>
                                            <td class="text-right danger"><strong>'.number_format($reg["sumahaber"],2).'</strong></td>
                                            <td class="text-right danger"><strong>'.number_format($total_d,2).'</strong></td>
                                            <td class="text-right danger"><strong>'.number_format($total_a,2).'</strong></td>
                                            ';
                                            
                                        } else {
                                            $content.='
                                            <td><strong>Totales:</strong> </td>
                                            <td class="text-right"><strong>'.number_format($reg['sumadebe'],2).'</strong></td>
                                            <td class="text-right"><strong>'.number_format($reg['sumahaber'],2).'</strong></td>
                                            <td class="text-right danger">$ '.number_format($total_d, 2).'</td>
                                            <td class="text-right danger">$ '.number_format($total_a, 2).'</td>
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
}
?>



<!--CONTENIDO DE LA PAGINA-->
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
    <title>Balance de Comprobación</title>
</head>

<body>
    <!-- Barra de navegación -->
    <?php include("nav.php"); ?>

    <!-- Contenido de la página -->
    <div class="container" id="contenido">
        <div class="row row-offcanvas row-offcanvas-right">
            <div class="col-xs-12 col-sm-9">
                <div class="page-header">
                    <?php $h1 = "Balance de Comprobacion";
                        echo '<h3>'.$h1.'</h3>'
                    ?>
                </div>
                <div class="row">
                    <div class="container">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="6">
                                    <!--Nombre de la entidad -->
                                        <h2 class="text-center">Balance de Comprobación</h2>
                                        <p align="center">
                                            <script>
                                                var month=new Array();
                                                month[0]="Enero";
                                                month[1]="Febrero";
                                                month[2]="Marzo";
                                                month[3]="Abril";
                                                month[4]="Mayo";
                                                month[5]="Junio";
                                                month[6]="Julio";
                                                month[7]="Agosto";
                                                month[8]="Septiembre";
                                                month[9]="Octubre";
                                                month[10]="Noviembre";
                                                month[11]="Diciembre";
                                                var fecha = new Date();
                                                document.write("Al " + fecha.getDate() + " de " + month[fecha.getMonth()] + " de " + fecha.getFullYear());
                                            </script>
                                        </p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>Cuenta</th>
                                    <th>Debe</th>
                                    <th>Haber</th>
                                    <th >Saldo debe</th>
                                    <th >Saldo haber</th>
                                </tr>
                                <?php 
                                error_reporting(E_ALL ^ E_NOTICE);
                                $total_a=0;
                                $total_d=0;
                                    $consulta = "SELECT DISTINCT(c.codigo_cuenta),c.subgrupo,SUM((c.saldo_debe)) sumdebe,SUM((c.saldo_haber)) sumhaber FROM cuentas c,subgrupos s WHERE c.subgrupo=s.codigo_subgrupo GROUP by c.subgrupo";
                                    $consulta = $conexion->query($consulta);
                                    /*Suma segun subgurupos de cuentas*/
                                while ($subg = $consulta->fetch_assoc()) {
                                    $sql = "SELECT * FROM cuentas where subgrupo='".$subg["subgrupo"]."' ";
                                    $ejecutar = $conexion->query($sql);
                                    $deudor=0;
                                    $acreedor=0;
                                    $sum_a=0;
                                    $sum_d=0;
                                    while($regs = $ejecutar->fetch_assoc()){
                                        echo "<tr>";
                                        echo "<td>".utf8_encode($regs["codigo_cuenta"])." ".utf8_encode($regs["nombre_cuenta"])."</td>";
                                        if($regs["saldo_debe"]==0){
                                            $deudor = 0; 
                                            $acreedor=$regs["saldo_haber"]-$regs["saldo_debe"];
                                            $sum_d=$sum_d+$deudor;
                                            $sum_a=$sum_a+$acreedor;
                                            echo "<td class='text-right'>".number_format($regs["saldo_debe"],2)."</td>";
                                            echo "<td class='text-right'>".number_format($regs["saldo_haber"],2)."</td>";
                                            echo "<td align='right'>$ ".number_format($deudor, 2)."</td>";
                                            echo "<td align='right'>$ ".number_format($acreedor, 2)."</td>";
                                        } elseif ($regs["saldo_haber"]==0){
                                                    $deudor =$regs["saldo_debe"]-$regs["saldo_haber"]; 
                                                    $acreedor=0;
                                                    $sum_d=$sum_d+$deudor;
                                                    $sum_a=$sum_a+$acreedor;
                                                    echo "<td class='text-right'>".number_format($regs["saldo_debe"],2)."</td>";
                                                    echo "<td class='text-right'>".number_format($regs["saldo_haber"],2)."</td>";
                                                    echo "<td align='right'>$ ".number_format($deudor, 2)."</td>";
                                                    echo "<td align='right'>$ ".number_format($acreedor, 2)."</td>";
                                                }elseif ($regs["saldo_debe"]<$regs["saldo_haber"]) {
                                                     $deudor = 0; 
                                                    $acreedor=$regs["saldo_haber"] - $regs["saldo_debe"];
                                                    $sum_d=$sum_d+$deudor;
                                                    $sum_a=$sum_a+$acreedor;
                                                    echo "<td class='text-right'>".number_format($regs["saldo_debe"],2)."</td>";
                                                    echo "<td class='text-right'>".number_format($regs["saldo_haber"],2)."</td>";
                                                    echo "<td align='right'>$ ".number_format($deudor, 2)."</td>";
                                                    echo "<td align='right'>$ ".number_format($acreedor, 2)."</td>";
                                                }
                                                elseif ($regs["saldo_debe"]>$regs["saldo_haber"]) {
                                                    $deudor = $regs["saldo_debe"]-$regs["saldo_haber"];
                                                    $acreedor = 0;
                                                    $sum_d=$sum_d+$deudor;
                                                    $sum_a=$sum_a+$acreedor;
                                                    echo "<td class='text-right'>".number_format($regs["saldo_debe"],2)."</td>";
                                                    echo "<td class='text-right'>".number_format($regs["saldo_haber"],2)."</td>";
                                                    echo "<td align='right'>$ ".number_format($deudor, 2)."</td>";
                                                    echo "<td align='right'>$ ".number_format($acreedor, 2)."</td>";
                                                }
                                            echo "</tr>";
                                            $total_a=$total_a+$sum_a;
                                            $total_d=$total_d+$sum_d;
                                        }
                                    
                                    echo "<tr>";
                                    echo "<td class='text-right' colspan='3'><strong>Sumas Totales:</strong></td>";
                                    echo "<td align='right'>".number_format($sum_d,2)."</td>";
                                    echo "<td align='right'>".number_format($sum_a,2)."</td>";
                                    echo "</tr>";
                                }
                                    /*Total de todas las cuentas*/ 
                                    $sql = "SELECT SUM(saldo_debe) sumadebe, SUM(saldo_haber) sumahaber FROM cuentas";
                                    $ejecutar = $conexion->query($sql);
                                    echo "<tr>";
                                    while($reg = $ejecutar->fetch_assoc()){
                                        if($reg["sumadebe"]!=$reg["sumahaber"]){
                                            echo "<td class='danger'><strong>Totales:</strong> </td>";
                                            echo "<td class='text-right danger'><strong>".number_format($reg["sumadebe"],2)."</strong></td>";
                                            echo "<td class='text-right danger'><strong>".number_format($reg["sumahaber"],2)."</strong></td>";
                                            echo "<td class='text-right danger'><strong>".number_format($total_d,2)."</strong></td>";
                                            echo "<td class='text-right danger'><strong>".number_format($total_a,2)."</strong></td>";
                                        } else {
                                            echo "<td><strong>Totales:</strong> </td>";
                                            echo "<td class='text-right'><strong>".number_format($reg["sumadebe"],2)."</strong></td>";
                                            echo "<td class='text-right'><strong>".number_format($reg["sumahaber"],2)."</strong></td>";
                                            echo "<td class='text-right'><strong>".number_format($total_d,2)."</strong></td>";
                                            echo "<td class='text-right'><strong>".number_format($total_a,2)."</strong></td>";
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