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

        <script src="scripts1.0.4/globalize.js"></script>
        <script src="scripts1.0.4/dx.chartjs.js"></script>
        <script src="scripts1.0.4/estadisticas.js"></script>
    </head>

    <body>
    <div class="container">
        <!--<div id="bars-gastos" class="charts-containers"></div>-->
        <div id="torta-gastos" class="charts-containers"></div>
        <div id="torta-ingresos" class="charts-containers"></div>
        <div id="torta-entregas" class="charts-containers"></div>
    </div>
    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>
