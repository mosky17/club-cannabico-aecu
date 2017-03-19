<?php

require_once(dirname(__FILE__) . '/vista_layout.php');

?>

    <head>
        <script src="scripts1.0.10/globalize.js"></script>
        <script src="scripts1.0.10/dx.chartjs.js"></script>
        <script src="scripts1.0.10/vista_socio.js"></script>
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
            </div>
        </div>

        <!-- Recordatorio Deuda -->
        <div class="deudas" style="display:none;">
        <h3>Pagos Adeudados</h3>
        <div class="socioRecordatorioDeudaContainer"></div>
        </div>

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
                        <th>Descuento $</th>
                        <th>Tipo</th>
                    </tr>
                    </thead>
                    <tbody id="listaPagosSocioTabla"></tbody>
                </table>
            </div>
        </div>

        <!--Entregas-->
        <h3 id="socioEntregasTitulo">Repartos</h3>

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
        <div id="torta-entregas" class="charts-containers" style="width: 100%;margin-top: 10px;"></div>
    </div>

    </body>

<?
require_once(dirname(__FILE__) . '/footer.php');

?>
