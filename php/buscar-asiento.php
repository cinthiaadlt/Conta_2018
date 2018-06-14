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
	<title>Buscar Ficha</title>
</head>

<body>
	<!-- Barra de navegación -->
	<?php include("nav.php"); ?>

	<!-- Contenido de la página -->
	<div class="container" id="contenido">
		<div class="row row-offcanvas row-offcanvas-right">
			<div class="col-xs-12 col-sm-9">
				<div class="page-header">
        			<h3>Buscar Ficha</h3>
        		</div>
        		<div class="row">
        			<div class="row">
            			<div class="col-lg-12 well">
                        <!--
            			    <h2 class="text-primary"><span class="glyphicon glyphicon-info-sign"></span> Buscar Asientos</h2>
                            -->
            			    <p align="justify" class="text-info">
            			        Si usted necesita obtener los datos de algún asiento, simplemente ingrese el número de asiento correspondiente a la asiento que desea buscar y el sistema le mostrará la información relacionada. Si no existen registros con el código ingresado, el sistema se lo notificará inmediatamente.
            			    </p>
            			</div>
            			<hr>
            			<form action="busquedas-asientos.php" class="form-inline" id="buscar" name="buscar_frm" method="get" enctype="application/x-www-form-urlencoded">
            				<label for="busqueda" class="control-label">Número de ficha</label>
            				<br><br>
            				<div class="row">
            					<div class="col-lg-6">
            						<input type="number" min="1" id="busqueda" name="buscar" class="form-control" placeholder="Ingrese el número de ficha a buscar" required/>
            					</div>
            					<div class="col-lg-6">
            						<button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-search"></span>&nbsp; Buscar</button>
            					</div>
            				</div>
            			</form>
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