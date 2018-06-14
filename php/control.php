<?php 
	include("conexion.php");

	$usuario = $_POST["user_txt"];
	$password = $_POST["password_txt"];
	$consulta = "SELECT usuario FROM usuario WHERE usuario='$usuario' AND password=SHA1('$password')"; /*SHA1('$password')*/
	
	$ejecutar_consulta = $conexion->query($consulta);

	$regs = $ejecutar_consulta->num_rows;
	
	if($regs!=0)
	{
		session_start();

		$_SESSION["autentificado"]=true;
		$_SESSION["usuario"]=$_POST["user_txt"];
		setcookie("sesion",$_SESSION["autentificado"],time()+3600,"/");
		header("Location: home.php");
	}

	else
	{
		header("Location: ../index.php?error=si");
	}
?>