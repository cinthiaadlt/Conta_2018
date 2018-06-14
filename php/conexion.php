<?php
	function conectarse()
	{
		$servidor 	=	 "localhost";
		$usuario 	=	 "root";
		$password 	=	 "";
		$bd 		=	 "sic115";

		$conectar = new mysqli($servidor, $usuario, $password, $bd);
		    return $conectar;
	}

	$conexion = conectarse();
?>