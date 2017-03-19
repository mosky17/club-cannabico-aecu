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
        <script src="scripts1.0.10/pago.js"></script>
    </head>

    <body>
    <div class="container">
        <div class="socioHeader">
            <h2 id="pagoNombreTitulo" style="float: left;"></h2>
            <div style="float: left;margin: 25px 0 0 10px;"><a href="#" onclick="Pago.Editar();return false;">editar</a></div>
            <span id="pagoLabelEstado" class="label labelCancelado">CANCELADO</span>
            <div id=feedbackContainer></div>
        </div>

        <div class="box row-fluid">
            <div class="span6">
                <div style="" class="socioDatosField">
                    <h4>Monto $</h4>
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
                    <div id="pagoEditarRazon" style="display:none;">
                        <input type="text" placeholder="" id="pagoEditarRazonRazon" style="margin-left: 23px;width:100px;">
                    </div>
                </div>
                <div style="" class="socioDatosField">
                    <h4>Descuento $</h4>
                    <div style="margin: 0;padding: 10px;" id="pagoDatosValorDescuento" class="socioDatosValor"></div>
                    <div id="pagoEditarDescuento">
                        <input type="text" placeholder="0.00" id="pagoEditarDescuentoDescuento" style="margin-left: 23px;width:100px;">
                        <select style="margin: 0 0 0 10px;" id="pagoEditarDescuentoRazonDescuento">
                            <option value="Voluntariado">Voluntariado</option>
                            <option value="Resolucion directiva">Resoluci&oacute;n directiva</option>
                            <option value="Otra">Otra</option>
                        </select>
                    </div>
                </div>
            </div>
            <div style="display:none;" class="span12" id="pagoBtnCancelarContainer">
                <div class="btn btn-danger" id="pagoBtnCancelar">Cancelar Pago</div>
            </div>
        </div>
        <div style="display: block;width: 100px;margin: 10px 0;float: right;" class="btn btn-primary" id="pagoBtnSalvar" onclick="Pago.Salvar();">Salvar</div>
    </div>

    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>
