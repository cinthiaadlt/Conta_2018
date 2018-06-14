<?php 
include("conexion.php");
include("funciones.php");
$res = calculaPlanilla($conexion, $_POST);
if($res==1){
	header("Location: alta-empleado.php?error=no");
} else if($res==0){
	header("Location: alta-empleado.php?error=si");
}
?>