<?php

require_once(dirname(__FILE__) . '/vista_layout.php');

?>

    <head>
        <script src="scripts1.0.7/vista_socio.js"></script>
    </head>

    <body>
    <div class="container">
        <div class="socioHeader">
            <h2 id="socioNombreTitulo"></h2>
            <span id="socioLabelEstado" class="label"></span>
            <div id=feedbackContainer></div>
        </div>

        <div class="box row-fluid">
            <div class="span6">

                <div style="display:none;" id="socioDatosFieldNombre" class="socioDatosField">
                    <h4>Nombre</h4>

                    <div id="socioDatosValorNombre" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>N&uacute;mero</h4>

                    <div id="socioDatosValorNumero" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Email</h4>

                    <div id="socioDatosValorEmail" class="socioDatosValor"></div>
                </div>
            </div>
            <div class="span6">
                <div class="socioDatosField">
                    <h4>Fecha Inicio</h4>

                    <div id="socioDatosValorFechaInicio" class="socioDatosValor"></div>
                </div>
                <div class="socioDatosField">
                    <h4>Tags</h4>

                    <div id="socioDatosValorTags" class="socioDatosValor"></div>
                </div>
            </div>
        </div>

        <!-- Recordatorio Deuda -->
        <h3>Pagos Adeudados</h3>
        <div class="socioRecordatorioDeudaContainer"></div>

        <!--Pagos-->
        <h3 id="socioPagoseTitulo">Pagos</h3>

        <div class="box row-fluid">
            <div class="span12 socioListaContenedor">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Valor $</th>
                        <th>Fecha Pago</th>
                        <th>Raz&oacute;n</th>
                        <th>Tipo</th>
                    </tr>
                    </thead>
                    <tbody id="listaPagosSocioTabla"></tbody>
                </table>
            </div>
        </div>

        <!--Entregas-->
        <h3 id="socioEntregasTitulo">Entregas</h3>

        <div class="box row-fluid">
            <div class="span12 socioListaContenedor">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Peso (gr)</th>
                        <th>Fecha Entregado</th>
                        <th>Variedad</th>
                    </tr>
                    </thead>
                    <tbody id="listaEntregasSocioTabla"></tbody>
                </table>
            </div>
        </div>
    </div>

    </body>

<?
require_once(dirname(__FILE__) . '/footer.php');

?>
