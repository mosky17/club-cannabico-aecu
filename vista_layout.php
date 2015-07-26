<?php
session_start();

require_once(dirname(__FILE__).'/config.php');
require_once(dirname(__FILE__).'/proc/classes/dato.php');

$datos = Dato::get_datos();

?>

<script type="text/javascript">

    var DATO_nombre = "<?php echo $datos["nombre"]; ?>";
    var DATO_sigla = "<?php echo $datos["sigla"]; ?>";
    var DATO_logo = "<?php echo $datos["logo"]; ?>";

</script>

<!DOCTYPE html>
<head>
    <title><?php echo $datos["nombre"]; ?></title>
    <link href="styles1.0.7/bootstrap_old.css" rel="stylesheet" media="screen">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="styles1.0.7/main.css" rel="stylesheet">
    <link href="styles1.0.7/vista.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="scripts1.0.7/bootstrap.js"></script>
    <script src="scripts1.0.7/toolbox.js"></script>
</head>

<div id="headerNavigation" class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <a class="navbar-brand brand" id="nav_brand"><?php echo $datos["nombre"]; ?></a>
        <img id="nav_loader" src="images/loader.gif">
    </div>
</div>
