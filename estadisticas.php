<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 03/08/13
 * Time: 07:26 PM
 * To change this template use File | Settings | File Templates.
 */

require_once(dirname(__FILE__) . '/layout.php');
require_once(dirname(__FILE__) . '/proc/classes/auth.php');

if (Auth::access_level() < 0) {
    ?>
    <script type="text/javascript">
        Toolbox.GoToLogin();
    </script>
<?php } else { ?>

    <head>
        <title>Club Social de Cannabis #1</title>
        <script src="scripts/globalize.js"></script>
        <script src="scripts/dx.chartjs.js"></script>
        <script src="scripts/estadisticas.js"></script>
    </head>

    <body>
    <div class="container">
        <!--<div id="bars-gastos" class="charts-containers"></div>-->
        <div id="torta-gastos" class="charts-containers"></div>
        <div id="torta-ingresos" class="charts-containers"></div>
        <div id="torta-entregas" class="charts-containers"></div>
    </div>
    </body>

<?php } ?>
