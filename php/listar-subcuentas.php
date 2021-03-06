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
	<title>Listado de Subcuentas</title>
</head>

<body>
	<!-- Barra de navegación -->
	<?php include("nav.php"); ?>

	<!-- Contenido de la página -->
	<div class="container" id="contenido">
		<div class="row row-offcanvas row-offcanvas-right">
			<div class="col-xs-12 col-sm-9">
				<div class="page-header">
        			<h3>Listado de subcuentas</h3>
        		</div>

        		<!-- Activos -->
        		<div class="row">
        			<div class="col-lg-12">
        				<?php
        					if(!isset($conexion)){
        						include("conexion.php");
        					}
        					$consulta = "SELECT
        								codigo_subcuenta, nombre_subcuenta
        								FROM subcuentas";

        					$ejecutar_consulta = $conexion->query($consulta);

        					echo "<div>";
							echo "<table class='table table-hover table-bordered table-striped  table-condensed table-responsive text-left'>";
							echo "<thead>";
							echo "<tr>";
							echo "<th width='110px' class='text-center'>Subcuenta</th>";
							echo "<th class='text-center'>Nombre</th>";
							echo "</tr>";
							echo "</thead>";
							echo "<tbody>";

							while($registro = $ejecutar_consulta->fetch_assoc()){
								$codigo_subcuenta = $registro["codigo_subcuenta"];
								echo "<tr>";
								echo "<td class='text-right'><a href='detalle-subcuenta.php?subcuenta=$codigo_subcuenta'>".utf8_encode($registro["codigo_subcuenta"])."</a></td>";
								echo "<td><a href='detalle-subcuenta.php?subcuenta=$codigo_subcuenta'>".utf8_encode($registro["nombre_subcuenta"])."</a></td>";
								echo "</tr>";
							}

							echo "</tbody>";
							echo "</table>";
							echo "</div>";
        				?>
        			</div>
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
