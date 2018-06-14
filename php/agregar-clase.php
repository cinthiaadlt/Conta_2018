<?php 
require 'conexion.php';
	include("sesion.php");
	if(!$_COOKIE["sesion"]){
		header("Location: salir.php");
	}
		if($_SESSION["tipo"]=="estandar"){
		header("Location: home.php?error=acceso-denegado");
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
	<script src="../js/jquery.maskedinput.js" type="text/javascript"></script>
	<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
	<title>Crear Cuentas</title>
</head>

<body>
	<!-- Barra de navegación -->
	<?php include("nav.php"); ?>

	<!-- Contenido de la página -->
	<div class="container" id="contenido">
		<div class="row row-offcanvas row-offcanvas-right">
			<div class="col-xs-12 col-sm-9">
				<div class="page-header">
					<h3>Registrar anio contable</h3>
				</div>				
				<div >
					<hr>
					<?php 
					error_reporting(E_ALL ^ E_NOTICE);
					$error=$_GET["error"];
					$mensaje = $_GET["mensaje"];

					switch ($error) {
						case 'si':
							echo "<div class='alert alert-danger'>";
							echo $mensaje;
							echo "</div>";
							break;
						case 'no':
							echo "<div class='alert alert-success alert-dismissable'>";
							echo "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
							echo $mensaje;
							echo "</div>";
					}
					?>

					<!-- Crear Clase 			ARREGLAR-->
					
					<div class="col-lg-12 well">
						<form action="" class="form-horizontal" method="post" name="crear_clase_frm" enctype="application/x-www-form-urlencoded">
							<fieldset>
								<legend>Agregar anio</legend>
								<div class="container">
									<div class="row">
										<div class="col-lg-12">
											<label for="nombre_clase" class="control-label">Anio</label>
											<input type="text" name="anio" class="form-control" title="Escriba un nombre para la clase" required/>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-lg-12">
											<input class="btn btn-success pull-right" type="submit" name="registrar_datos" value="REGISTRAR">
										</div>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					
				</div>
				<?php
//include('conexion.php');
if (isset($_POST['registrar_datos'])) {
    if ($_POST['anio'] == '') {
        echo 'Por favor llene todos los campos.';
    } else {
        $rs = $conexion->query("SELECT MAX(id) AS iden FROM anio_contable");
        if ($row = $rs->fetch_row()) {
            $iden = trim($row[0]);
        }
        $id = $iden + 1;
        $anio = $_POST['anio'];
        $sqenti = "INSERT INTO anio_contable(id, anio_contable) VALUES ('$id','$anio');";
        $conexion->query($sqenti);

        $mensaje = "Usted ha registrado correctamente.";
        print "<script>alert('$mensaje'); window.location='home.php';</script>";
    }
}
?>
				<hr>
			</div><!--/span-->

			<!-- Barra lateral o sidebar -->
			<?php include("sidebar.php"); ?>
			
		</div>
	</div>

	<!-- Pie de página o Footer -->
	<?php include("footer.php"); ?>

	<!-- Ventanas flotantes -->
	<?php include("modal.php"); ?>

	<script>
	jQuery(function($){
		$("#corr_grupo").mask("9?9", {placeholder:" "});
		$("#corr_subgrupo").mask("9?9", {placeholder:" "});
		$("#corr_cuenta").mask("9?9", {placeholder:" "});
		$("#corr_subcuenta").mask("9?9", {placeholder:" "});
	});
	</script>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>