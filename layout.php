<?php
session_start();

require_once(dirname(__FILE__).'/proc/classes/auth.php');
require_once(dirname(__FILE__).'/config.php');

?>

<script type="text/javascript">
    var GLOBAL_domain = "<?php echo $GLOBALS['domain']; ?>";
    var GLOBAL_short_name = "<?php echo $GLOBALS['short_name']; ?>";
    var GLOBAL_name = "<?php echo $GLOBALS['name']; ?>";
</script>

<!DOCTYPE html>
<head>
    <title><?php echo $GLOBALS['name']; ?></title>
    <link href="styles1.0.2/bootstrap_old.css" rel="stylesheet" media="screen">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="styles1.0.2/main.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery.js"></script>
    <script src="scripts1.0.2/bootstrap.js"></script>
	<script src="scripts1.0.2/toolbox.js"></script>
	<script src="scripts1.0.2/jquery.maskedinput.js"></script>
</head>

<div id="headerNavigation" class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
	    <a class="navbar-brand brand" id="nav_brand"><?php echo $GLOBALS['name']; ?></a>
	    <ul class="nav">
		    <li class="nav_lista_link" id="nav_lista_socios"><a href="<?php echo $GLOBALS['domain']; ?>">Socios</a></li>
            <li class="nav_lista_link" id="nav_lista_pagos"><a href="<?php echo $GLOBALS['domain']; ?>/lista_pagos.php">Pagos</a></li>
            <li class="nav_lista_link" id="nav_lista_gastos"><a href="<?php echo $GLOBALS['domain']; ?>/lista_gastos.php">Caja</a></li>
            <li class="nav_lista_link" id="nav_lista_estadisticas"><a href="<?php echo $GLOBALS['domain']; ?>/estadisticas.php">Estadisticas</a></li>
            <li class="nav_lista_link" id="nav_lista_admins"><a href="<?php echo $GLOBALS['domain']; ?>/lista_admins.php">Admins</a></li>
	    </ul>

        <a id="nav_logout" href="#" onClick="Toolbox.Logout(); return false;">salir</a>
        <p id="admin_header_name"><?php echo Auth::get_admin_nombre() . " | "?></p>
		<img id="nav_loader" src="images/loader.gif">
    </div>
</div>
