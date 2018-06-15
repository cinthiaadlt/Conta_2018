<?php
session_start();
require 'conexion.php';
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
  <title>Registro entidad</title>
  <script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
  <script src="../js/jquery.maskedinput.js" type="text/javascript"></script>
</head>

  <body style="background-color: #1F8B1F">

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

    <div id="login-page">
      <div class="container">

          <form class="form-horizontal style-form" action="" method="post" >
            <div >
                    <br><br>
                    <div >
                        <div class="modal-content" >
                            <div class="modal-header">
                               <center> <h4 style="font-size: 20px;" class="modal-title"> REGISTRO DE ENTIDAD </h4></center>
                            </div>
                            <div class="modal-body">
                                <table width="90%" >
                                <tr>
                                    <td width="10%"></td>
                                      <td>
                                        <center><h4 class="mb"><i class="fa fa-angle-right"></i> Datos de la entidad</h4></center>
                                        <div class="form-group">
                                          <label class="col-sm-2 col-sm-2 control-label">Nombre: </label>
                                          <div class="col-sm-10">
                                              <input type="text" class="form-control" name="nombre_entidad" required="true">
                                          </div>
                                        </div>

                                        <div class="form-group">
                                          <label class="col-sm-2 col-sm-2 control-label">Direccion: </label>
                                          <div class="col-sm-10">
                                              <input type="text" class="form-control" name="direccion_entidad" required="true">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-sm-2 col-sm-2 control-label">Telefono: </label>
                                            <div class="col-sm-10">
                                              <input type="tel" class="form-control" name="fono1" required="true" onkeypress="return valida(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-sm-2 col-sm-2 control-label">Ciudad: </label>
                                          <div class="col-sm-10">
                                              <input type="text" class="form-control" name="ciudad_entidad" required="">
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                              </table>
                                        <center>
                                        <h4 class="mb"><i class="fa fa-angle-right"></i> Datos del responsable</h4>
                                        <table >
                                            <tr>
                                              <td width="10%"></td>
                                              <td width="30%">
                                                  <div class="form-group">
                                                  <label class="col-sm-2 col-sm-2 control-label">Usuario:&emsp; </label>
                                                  <div class="col-sm-8">
                                                      <input type="text" class="form-control" name="usuario">
                                                  </div>
                                                </div>
                                              </td>
                                              <td width="2%"></td>
                                              <td width="30%">
                                              <div class="form-group">
                                                  <label class="col-sm-3 col-sm-3 control-label">Contraseña:&emsp; </label>
                                                  <div class="col-sm-8">
                                                      <input type="password" class="form-control" name="contraseña">
                                                  </div>
                                              </div>
                                            <br>
                                              </td>
                                            </tr>
                                          </table>
                                          <center><h4 class="mb"><i class="fa fa-angle-right"></i> Anio Contable</h4>
                                                <center>  <div class="form-group">
                                                  <label class="col-sm-2 col-sm-2 control-label">Anio:&emsp; </label>
                                                  <center><div class="col-sm-8">
                                                      <input type="text" class="form-control" name="anio">
                                                  </div>
                                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit"  class="btn btn-default" name="cancelar" value="CANCELAR">
                                <input type="submit"  class="btn btn-theme" name="registrar_datos" value="REGISTRAR DATOS">
                            </div>
                        </div>
                    </div>
                </div>
                <?php
//include('conexion.php');
if (isset($_POST['registrar_datos'])) {
    if ($_POST['nombre_entidad'] == '' or $_POST['usuario'] == '' or $_POST['contraseña']=='') {
        echo 'Por favor llene todos los campos.';
    } else {
        $rs = $conexion->query("SELECT MAX(id) AS iden FROM entidad");
        if ($row = $rs->fetch_row()) {
            $iden = $row[0];
        }
        $id = $iden + 1;

        $hoy = date('Y-m-d');

        $nombre = $_POST['nombre_entidad'];
        $direccion = $_POST['direccion_entidad'];
        $telefono = $_POST['fono1'];
        $ciudad = $_POST['ciudad_entidad'];
        $usuario = $_POST['usuario'];
        $contraseña = $_POST['contraseña'];
        $anio = $_POST['anio'];

        $sqenti = "INSERT INTO entidad(id, nombre, direccion, telefono, ciudad) VALUES ('$id','$nombre' , '$direccion', '$telefono', '$ciudad');";
        $conexion->query($sqenti);

        $sqenti = "INSERT INTO usuario(usuario, password, fecha, tipo) VALUES ('$usuario',sha1('$contraseña'),'$hoy', 'administrador');";
        $conexion->query($sqenti);

        $sqenti = "INSERT INTO anio_contable(id, anio_contable) VALUES ('$id','$anio');";
        $conexion->query($sqenti);

        $mensaje = "Usted se ha registrado correctamente.";
        print "<script>alert('$mensaje'); window.location='../index.php';</script>";
    }
}
?>
          </form>
      </div>
    </div>
<br><br>
    <script>
    function valida(e){
        tecla = (document.all) ? e.keyCode : e.which;

        //Tecla de retroceso para borrar, siempre la permite
        if (tecla==8){
            return true;
        }

        // Patron de entrada, en este caso solo acepta numeros
        patron =/[0-9]/;
        tecla_final = String.fromCharCode(tecla);
        return patron.test(tecla_final);
    }
    </script>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="assets/js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("assets/img/fon.jpg", {speed: 500});
    </script>


  </body>
</html>
