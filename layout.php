<?php
session_start();

require_once(dirname(__FILE__).'/proc/classes/auth.php');
require_once(dirname(__FILE__).'/config.php');
require_once(dirname(__FILE__).'/proc/classes/dato.php');

$datos = Dato::get_datos();
if(empty($datos["nombre"]) && empty($datos["siglas"])){
    Dato::actualizar_dato("nombre",$GLOBALS['name']);
    Dato::actualizar_dato("sigla",$GLOBALS['short_name']);
    $datos = Dato::get_datos();
}

?>

<script type="text/javascript">
    var GLOBAL_domain = "<?php echo $GLOBALS['domain']; ?>";
    var GLOBAL_short_name = "<?php echo $datos["sigla"]; ?>";
    var GLOBAL_name = "<?php echo $datos["nombre"]; ?>";

    var DATO_nombre = "<?php echo $datos["nombre"]; ?>";
    var DATO_sigla = "<?php echo $datos["sigla"]; ?>";
    var DATO_personeriajuridica = "<?php echo $datos["personeriajuridica"]; ?>";
    var DATO_idircca = "<?php echo $datos["idircca"]; ?>";
    var DATO_logo = "<?php echo $datos["logo"]; ?>";
    var DATO_cajacerrada = "<?php echo $datos["cajacerrada"]; ?>";
</script>

<!DOCTYPE html>
<head>
    <title><?php echo $datos["nombre"]; ?></title>
    <link href="styles1.0.9/bootstrap_old.css" rel="stylesheet" media="screen">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="styles1.0.9/main.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery.js"></script>
    <script src="scripts1.0.10/bootstrap.js"></script>
	<script src="scripts1.0.10/toolbox.js"></script>
	<script src="scripts1.0.10/jquery.maskedinput.js"></script>
</head>

<div id="headerNavigation" class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
	    <a class="navbar-brand brand" id="nav_brand"><?php echo $datos["nombre"]; ?></a>
	    <ul class="nav">
            <li class="nav_lista_link" id="nav_datos"><a href="<?php echo $GLOBALS['domain']; ?>/datos.php">Datos</a></li>
		    <li class="nav_lista_link" id="nav_lista_socios"><a href="<?php echo $GLOBALS['domain']; ?>">Socios</a></li>
            <li class="nav_lista_link" id="nav_lista_pagos"><a href="<?php echo $GLOBALS['domain']; ?>/lista_pagos.php">Pagos</a></li>
            <li class="nav_lista_link" id="nav_lista_gastos"><a href="<?php echo $GLOBALS['domain']; ?>/lista_gastos.php">Caja</a></li>
            <li class="nav_lista_link" id="nav_lista_estadisticas"><a href="<?php echo $GLOBALS['domain']; ?>/estadisticas.php">Estadisticas</a></li>
            <li class="nav_lista_link" id="nav_lista_entregas"><a href="<?php echo $GLOBALS['domain']; ?>/lista_entregas.php">Entregas</a></li>
            <li class="nav_lista_link" id="nav_lista_admins"><a href="<?php echo $GLOBALS['domain']; ?>/lista_admins.php">Admin</a></li>
<!--            <li class="nav_lista_link" id="nav_informes"><a href="--><?php //echo $GLOBALS['domain']; ?><!--/informes.php">Informes</a></li>-->
	    </ul>

        <a id="nav_logout" href="#" onClick="Toolbox.Logout(); return false;">salir</a>
        <p id="admin_header_name"><?php echo Auth::get_admin_nombre() . " | "?></p>
		<img id="nav_loader" src="images/loader.gif">
    </div>
</div>
