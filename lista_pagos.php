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
        <title>Club Social de Cannabis #1</title>
        <script src="scripts1.0.1/lista_pagos.js"></script>
    </head>

    <body>
    <div class="container">
        <h2>Lista de Pagos</h2>
        <div class="controlesLista">
            <div id="exportarListaDropdown" class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    Exportar
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                    <li><a tabindex="-1" href="#" onClick="ListaPagos.ExportarComoListaPagosPorSocio();">Como lista de pagos
                            por socio</a></li>
                    <li><a tabindex="0" href="#" onClick="ListaPagos.ExportarComoListaTotalPagoPorSocio();">Como total pago por socio</a></li>
                    <li><a tabindex="1" href="#" onClick="ListaPagos.ExportarComoListaPagosPorMes();">Como lista de pagos
                            por mes</a></li>
                </ul>
            </div>
        </div>
        <div id=feedbackContainer></div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Valor $</th>
                <th>Fecha Pago</th>
                <th>Raz&oacute;n</th>
                <th>Notas</th>
                <th>Tipo</th>
                <th>Socio</th>
            </tr>
            </thead>
            <tbody id="listaPagosTabla"></tbody>
        </table>
    </div>

    <iframe id="exportIframe" src="" style="height:0px;border:0 none;"></iframe>

    </body>

<?php }

require_once(dirname(__FILE__) . '/footer.php');

?>