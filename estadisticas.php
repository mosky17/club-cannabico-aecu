<?php

require_once(dirname(__FILE__) . '/layout.php');
require_once(dirname(__FILE__) . '/proc/classes/auth.php');

if (Auth::access_level() < 0) {
    ?>
    <script type="text/javascript">
        Toolbox.GoToLogin();
    </script>
<?php } else { ?>

    <head>

        <script src="scripts1.0.10/globalize.js"></script>
        <script src="scripts1.0.10/dx.chartjs.js"></script>
        <script src="scripts1.0.10/estadisticas.js"></script>
    </head>

    <body>
    <div class="container">
        <!--<div id="bars-gastos" class="charts-containers"></div>-->
        <select id="select-torta-gastos" onchange="Estadisticas.OnChangeSelectTortaGastos();">
            <option value="comienzo">Desde el comienzo</option>
            <option value="12">&Uacute;ltimos 12 meses</option>
            <option value="3">&Uacute;ltimos 90 d&iacute;as</option>
            <option value="1">&Uacute;ltimos 30 d&iacute;as</option>
        </select>
        <div id="torta-gastos" class="charts-containers"></div>
        <div id="torta-ingresos" class="charts-containers"></div>
        <div id="torta-entregas" class="charts-containers"></div>
        <div id="torta-descuentos" class="charts-containers"></div>
    </div>
    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>
