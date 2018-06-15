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
	<title>Inicio</title>
</head>

<body>
	<!-- Barra de navegación -->
	<?php include("nav.php"); ?>
	
	<!-- Contenido de la página -->
	<div class="container" id="contenido">
		<div class="row row-offcanvas row-offcanvas-left">		

			<div class="col-sm-9 col-xs-12">
				<?php include("mensajes.php"); ?>
				<div class="page-header">
        			<h3>Bienvenido/a, <?php echo "<em style='text-transform:capitalize'>" . $_SESSION['usuario'] . "</em>"; ?></h3>
        		</div>
          		<div class="row">
          			<div class="col-lg-12 well">
          				<h3>Recomendaciones</h3>
          				<p align="justify">
          					Si usted desea ver buenos resultados con el uso de este sistema, deberá ingresarle información coherente y ordenada. Sin embargo, puede despreocuparse al momento de ingresar datos en formatos no admitidos, pues el sistema validará todas las entradas para obtener los datos en los formatos adecuados.
          				</p>
          				<p align="justify">
          					<span class="label label-warning">Importante:</span> La duración de la sesión está preconfigurada en 60 minutos. Luego de este tiempo la sesión se cerrará, todo esto con el objetivo de aumentar la seguridad y evitar los accesos no autorizados que podrían suceder si se dejara el equipo sin la supervisión adecuada por un largo período de tiempo.
          				</p>
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