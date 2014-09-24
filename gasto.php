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
        <script src="scripts1.0.2/gasto.js"></script>
    </head>

    <body>
    <div class="container">
        <div class="socioHeader">
            <h2 id="gastoNombreTitulo"></h2>
            <span id="gastoLabelEstado" class="label labelCancelado">CANCELADO</span>
            <div id=feedbackContainer></div>
        </div>

        <div class="box row-fluid">
            <div class="span6">

                <div style="display:none;" class="socioDatosField">
                    <h4>Valor $</h4>

                    <div id="gastoDatosValorValor" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Fecha Pago</h4>

                    <div id="gastoDatosValorFechaGasto" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Tipo</h4>

                    <div id="gastoDatosValorTipo" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Notas</h4>

                    <div id="gastoDatosValorNotas" class="socioDatosValor"></div>
                </div>

            </div>
            <div class="span6">
                <div class="socioDatosField">
                    <h4>Raz&oacute;n</h4>

                    <div id="gastoDatosValorRazon" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Socio</h4>

                    <div id="gastoDatosValorSocio" class="socioDatosValor"></div>
                </div>
            </div>
            <div style="display:none;" class="span12" id="gastoBtnCancelarContainer">
                <div class="btn btn-danger" id="gastoBtnCancelar">Cancelar Gasto</div>
            </div>
        </div>

    </div>

    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>
