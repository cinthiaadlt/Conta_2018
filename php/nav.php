<div class="navbar navbar-inverse navbar-static-top">
	<div class="container">
		<!--<a href="home.php" class="navbar-brand">Computerized Accountancy System &#0153;</a> -->
		<a href="home.php" class="navbar-brand">Sistema Contable</a>
		<button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
<?php
			if(!isset($conexion)){
				include("conexion.php");
			}
			$usuario = $_SESSION["usuario"];
        	$consulta = "SELECT * FROM usuario WHERE usuario='$usuario'";
        	$ejecutar_consulta = $conexion->query($consulta); 
        	while($registro=$ejecutar_consulta->fetch_assoc()) 
				{
					$_SESSION["tipo"]=$registro["tipo"];
				}
			if($_SESSION['tipo']=="administrador" || $_SESSION['tipo']=="estandar" ){
?>
		<div class="collapse navbar-collapse navHeaderCollapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="home.php"><span class="glyphicon glyphicon-home"></span> &nbsp;Inicio</a></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Fichas <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="asiento-general.php"> ● &nbsp;Generales</a></li>
						<li><a href="asiento-simple.php"> ● &nbsp;Simples </a></li>
						<li class="divider"></li>
						<li><a href="diario.php"> ● &nbsp;Libro Diario General</a></li>
						
						<li class="divider"></li>
						<li><a href="buscar-asiento.php"> ● &nbsp;Buscar</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Cuentas <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="alta-cuentas.php"><span class="glyphicon glyphicon-plus-sign"></span> &nbsp;Crear cuentas</a></li>
						<li class="divider"></li>
						<li><a href="buscar-cuentas.php">● &nbsp;Búsquedas</a></li>
						<li class="divider"></li>
						<li><a href="actualizar.php">● &nbsp;Actualizaciones</a></li>
						<li class="divider"></li>
						<li><a href="catalogo-cuentas.php">● &nbsp;Catálogo General de Cuentas</a></li>
						<li><a href="listar-subcuentas.php">● &nbsp;Listado de Subcuentas</a></li>
						<li><a href="listar-cuentas.php">● &nbsp;Listado de Cuentas</a></li>
						<li><a href="listar-subgrupos.php"> ● &nbsp;Listado de Subgrupos</a></li>
					</ul>
				</li>
<?php
}
if($_SESSION['tipo']=="administrador" ){
?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Estados Financieros <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="mayor.php"> ● &nbsp;Mayor General</a></li>
						<li><a href="balance-comprobacion.php"> ● &nbsp;Balance de Comprobación</a></li>
						<li><a href="estado-resultados.php"> ● &nbsp;Estado de Resultados</a></li>
						<li><a href="balance-general.php"> ● &nbsp;Balance General</a></li>

					</ul>
				</li>
<?php
}
?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Acerca <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="#acerca" data-toggle="modal"><span class="glyphicon glyphicon-info-sign"></span> &nbsp;Acerca del sistema</a></li>
					</ul>
				</li>
					
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-transform:capitalize">
						<span class="glyphicon glyphicon-user"></span> &nbsp;<?php echo $_SESSION['usuario']; ?> 
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li><a href="cambio-pw.php"><span class="glyphicon glyphicon-lock"></span> &nbsp;Cambiar Contraseña</a></li>
						<li><a href="#logout" data-toggle="modal"><span class="glyphicon glyphicon-log-out"></span> &nbsp;Cerrar sesión</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>