<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 08/07/13
 * Time: 10:49 PM
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
        <script src="scripts1.0.1/pago.js"></script>
    </head>

    <body>
    <div class="container">
        <div class="socioHeader">
            <h2 id="pagoNombreTitulo"></h2>
            <span id="pagoLabelEstado" class="label labelCancelado">CANCELADO</span>
            <div id=feedbackContainer></div>
        </div>

        <div class="box row-fluid">
            <div class="span6">

                <div style="display:none;" class="socioDatosField">
                    <h4>Valor $</h4>

                    <div id="pagoDatosValorValor" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Fecha Pago</h4>

                    <div id="pagoDatosValorFechaPago" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Notas</h4>

                    <div id="pagoDatosValorNotas" class="socioDatosValor"></div>
                </div>

            </div>
            <div class="span6">
                <div class="socioDatosField">
                    <h4>Raz&oacute;n</h4>

                    <div id="pagoDatosValorRazon" class="socioDatosValor"></div>
                </div>
            </div>
            <div style="display:none;" class="span12" id="pagoBtnCancelarContainer">
                <div class="btn btn-danger" id="pagoBtnCancelar">Cancelar Pago</div>
            </div>
        </div>

    </div>

    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>
